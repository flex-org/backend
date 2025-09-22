<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Plans\Controllers\PlanController;
use App\Modules\Users\Controllers\UserAuthController;
use App\Modules\Features\Controllers\FeatureController;
use App\Modules\Platforms\Controllers\PlatformController;

Route::post('login', [UserAuthController::class, 'login']);
Route::post('signup', [UserAuthController::class, 'signUp']);
Route::post('resend-otp', [UserAuthController::class, 'resendOtp']);
Route::post('verify-email', [UserAuthController::class, 'verifyEmail'])
    ->middleware(['auth:sanctum', 'abilities:not-verified']);

Route::get('/features', [FeatureController::class, 'getActiveFeatures']);
Route::get('/plans', [PlanController::class, 'getActivePlans']);
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::delete('logout', [UserAuthController::class, 'logout']);
    Route::get('/domain-available/{domain}', [PlatformController::class, 'isDomainAvailable']);
    Route::post('platform/create', [PlatformController::class, 'store']);
    Route::get('platform/selling-systems', [PlatformController::class, 'sellingSystems']);
});