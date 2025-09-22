<?php
namespace App\Modules\Dashboard\Admins\Services;

use App\Facades\ApiResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Modules\Dashboard\Admins\Models\Admin;
use App\Modules\Utilities\Services\Auth\AuthServices;

class AdminAuthServices extends AuthServices
{
    public function login($credentials)
    {
        if (!$user = $this->checkUser($credentials)) {
            return ApiResponse::message(
                'Your credentials doesn\'t match our records',
                Response::HTTP_UNAUTHORIZED
            );
        }
        $domain = request()->header('domain');
        $this->abilities = ['dashboard', $domain];
        $token = $this->generateToken($user, 'dashboard');
        $data = $this->respondWithToken($user, $token);

        return ApiResponse::success($data, __('auth.loggedIn'));
    }

    function checkUser($credentials)
    {
        $user = Admin::whereEmail($credentials['email'])->first();
        if ($user && !Hash::check($credentials['password'], $user->password))
            return false;
        return $user;
    }
}
