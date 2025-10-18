<?php 
namespace App\Modules\V1\Utilities\Interfaces\Auth;

use Illuminate\Http\Request;
use App\Modules\V1\Utilities\Services\OtpService;
use App\Modules\V1\Utilities\Requests\OtpCheckRequest;
use App\Modules\V1\Utilities\Requests\EmailVerificationRequest;

interface AuthenticatableWithVerifiedRegisterInterface extends AuthenticatableInterface
{
    public function signUp(Request $signUpRequest, OtpService $otpService);

    public function verifyEmail(OtpCheckRequest $request, OtpService $otpService);

    public function resendOtp(EmailVerificationRequest $request, OtpService $otpService);
}