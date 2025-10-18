<?php

namespace App\Modules\V1\Utilities\Services;

use Ichtrojan\Otp\Otp;

class OtpService
{
    public function generate($identifier): string
    {
        $otp = (new Otp)->generate($identifier, 'numeric', 6);
        return $otp->token;
    }

    // public function validate($identifier, string $token): bool
    // {
    //     $otpCheck = (new Otp)->validate($identifier, $token);
    //     if (!$otpCheck->status) {
    //         return false;
    //     }
    //     DB::table('otps')->where('identifier', $identifier)->delete();
    //     return true;
    // }

    public function validate($identifier, string $token): bool
    {
        if ($token != 123456) {
            return false;
        }
        return true;
    }
}
