<?php

namespace App\Modules\V1\Users\Controllers;

use Illuminate\Http\Request;
use App\Modules\V1\Utilities\Services\OtpService;
use App\Modules\V1\Users\Services\UserAuthServices;
use App\Modules\V1\Utilities\Requests\OtpCheckRequest;
use App\Modules\V1\Utilities\Requests\EmailVerificationRequest;
use App\Modules\V1\Utilities\Interfaces\Auth\AuthenticatableWithVerifiedRegisterInterface;
use App\Modules\V1\Utilities\Requests\LoginRequest;

class UserAuthController implements AuthenticatableWithVerifiedRegisterInterface
{
    public function __construct(public UserAuthServices $authServices){}

    public function login(LoginRequest $loginRequest)
    {   
        return $this->authServices->login($loginRequest->only(['email','password']));
    }
    
    public function logout(Request $request)
    {
        return $this->authServices->logout($request);
    }

    public function signUp(Request $signUpRequest, OtpService $otpService)
    {
        $validatedData = $signUpRequest->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users",
            'phone' => "required|string|unique:users",
            'password' => 'required|string|min:7|confirmed',
        ]);

        return $this->authServices->signUp(
            $validatedData, 
            $otpService
        );

    }

    public function verifyEmail(OtpCheckRequest $request, OtpService $otpService)
    {
        return $this->authServices->verifyEmail(
            $request->validated(),
            $request->user(),
            $otpService
        );
    }

    public function resendOtp(EmailVerificationRequest $request, OtpService $otpService)
    {
        return $this->authServices->resendOtp(
            $request->validated(), 
            $otpService
        );
    }
    
}
