<?php 
namespace App\Modules\Utilities\Interfaces\Auth;

use Illuminate\Http\Request;
use App\Modules\Utilities\Services\OtpService;
use App\Modules\Utilities\Requests\OtpCheckRequest;
use App\Modules\Utilities\Requests\EmailVerificationRequest;

interface AuthenticatableWithVerifiedRegisterInterface extends AuthenticatableInterface
{
    public function signUp(Request $signUpRequest, OtpService $otpService);

    public function verifyEmail(OtpCheckRequest $request, OtpService $otpService);

    public function resendOtp(EmailVerificationRequest $request, OtpService $otpService);
}