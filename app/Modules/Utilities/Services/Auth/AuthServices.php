<?php

namespace App\Modules\Utilities\Services\Auth;

use App\Facades\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

Abstract class AuthServices
{
    public $abilities = [];

    abstract function checkUser($credentials);

    abstract public function login($credentials);

    public function generateToken($user, $tokenName): mixed
    {
        return $user->createToken($tokenName, $this->abilities)->plainTextToken;
    }

    public function respondWithToken($user, $token)
    {    
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => $user
        ];
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return ApiResponse::message(__('auth.logedOut'));
    }

}
