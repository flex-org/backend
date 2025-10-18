<?php

namespace App\Modules\V1\Users\Services;

use App\Models\V1\User;
use App\Facades\ApiResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Modules\V1\Utilities\Services\OtpService;
use App\Modules\V1\Utilities\Services\Auth\AuthWithVerifiedRegisterServices;

class UserAuthServices extends AuthWithVerifiedRegisterServices
{
    public function login($credentials)
    {
        if (!$user = $this->checkUser($credentials)) {
            return ApiResponse::message(
                'Your credentials doesn\'t match our records',
                Response::HTTP_UNAUTHORIZED
            );
        }

        if (is_null($user->email_verified_at)) {
            $this->abilities = ['not-verified'];
            $token = $this->generateToken($user, 'user');
            $data = $this->respondWithToken($user, $token);
            return ApiResponse::apiFormat([
                'data' => $data
            ],
            'use the code that sent to your mail to verify your accout',
            Response::HTTP_FORBIDDEN
        );
    }
    
        $this->abilities = ['verified'];
        $token = $this->generateToken($user, 'user');

        $data = $this->respondWithToken($user, $token);

        return ApiResponse::success($data, __('auth.loggedIn'));
    }
    
    public function signUp(array $userData, OtpService $otpService)
    {
        return DB::transaction(function () use($userData, $otpService) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'phone' => $userData['phone'],
                'password' => $userData['password'],
            ]);

            // $code = $otpService->generate($user);
            // $user->notify(new SendOtp($code));
            $this->abilities = ['not-verified'];
            $token = $this->generateToken($user, 'User Token');
            
            return ApiResponse::success([
                'token' => $token,
                'user' => $user
            ], __('auth.loggedIn'));
             
        });
    }

    public function verifyEmail(array $data, $user, OtpService $otpService)
    {
        if ($user->email_verified_at) {
            return ApiResponse::validationError([
                'email' => __('auth.already_verified')
            ]);
        }
        
        if (!$otpService->validate($user->email, $data['otp'])) {
            return ApiResponse::validationError([
                'otp' => __('auth.invalid_otp')
            ]);
        }
        
        $user = $user->update(['email_verified_at' => now()]);
        $user->tokens()->delete();
        $this->abilities = ['verified'];
        $token = $this->generateToken($user, 'User Token');
        $data = $this->respondWithToken(
            $user,
            $token, 
        );
        
        return ApiResponse::success($data, __('auth.verified_success'));

    }

    public function resendOtp(array $data, OtpService $otpService)
    {
        $user = User::where('email', $data['email'])->firstOrFail();

        // $code = $otpService->generate($user);
        // $user->notify(new SendOtp($code));

        return ApiResponse::message(__('auth.verification_resent'));
    }

    function checkUser($credentials)
    {
        $user = User::whereEmail($credentials['email'])->first();
        if ($user && !Hash::check($credentials['password'], $user->password))
            return false;
        return $user;
    }
}
