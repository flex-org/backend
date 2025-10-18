<?php

use App\Modules\V1\Dashboard\Admins\Controllers\AdminAuthController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AdminAuthController::class, 'login']);
Route::group(['middleware' => ['locale', 'auth:admins', 'domainAccess']], function () {
    Route::delete('logout', [AdminAuthController::class, 'logout']);
});