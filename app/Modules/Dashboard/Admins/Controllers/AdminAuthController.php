<?php
namespace App\Modules\Dashboard\Admins\Controllers;


use Illuminate\Http\Request;
use App\Modules\Utilities\Requests\LoginRequest;
use App\Modules\Dashboard\Admins\Services\AdminAuthServices;
use App\Modules\Utilities\Interfaces\Auth\AuthenticatableInterface;

class AdminAuthController implements AuthenticatableInterface
{
    public function __construct(public AdminAuthServices $authServices) {}

    public function login(LoginRequest $loginRequest)
    {   
        return $this->authServices->login($loginRequest->only(['email','password']));
    }
    
    public function logout(Request $request)
    {
        return $this->authServices->logout($request);
    }
    
}
