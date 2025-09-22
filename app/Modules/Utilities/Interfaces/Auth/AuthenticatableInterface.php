<?php 
namespace App\Modules\Utilities\Interfaces\Auth;

use Illuminate\Http\Request;
use App\Modules\Utilities\Requests\LoginRequest;

interface AuthenticatableInterface
{
    public function login(LoginRequest $loginRequest);
    
    public function logout(Request $request);
}