<?php
namespace App\Modules\V1\Dashboard\Admins\Controllers;


use Illuminate\Http\Request;
use App\Modules\V1\Utilities\Requests\LoginRequest;
use App\Modules\V1\Dashboard\Admins\Services\AdminAuthServices;
use App\Modules\V1\Utilities\Interfaces\Auth\AuthenticatableInterface;

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
