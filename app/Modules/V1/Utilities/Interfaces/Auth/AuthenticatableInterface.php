<?php 
namespace App\Modules\V1\Utilities\Interfaces\Auth;

use Illuminate\Http\Request;
use App\Modules\V1\Utilities\Requests\LoginRequest;

interface AuthenticatableInterface
{
    public function login(LoginRequest $loginRequest);
    
    public function logout(Request $request);
}