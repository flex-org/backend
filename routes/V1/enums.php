<?php

use App\Facades\ApiResponse;
use App\Models\V1\SellingSystem;
use Illuminate\Support\Facades\Route;
use App\Modules\V1\Utilities\enums\BillingCycle;
use App\Modules\V1\Platforms\Enums\PlatformSellingSystem;

    Route::get('selling-systems', function() {
        return ApiResponse::success(PlatformSellingSystem::options());
    });

    Route::get('billing-cycles', function() {
        return ApiResponse::success(SellingSystem::all());
    });
