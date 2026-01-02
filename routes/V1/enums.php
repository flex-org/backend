<?php

use App\Facades\ApiResponse;
use App\Models\V1\SellingSystem;
use Illuminate\Support\Facades\Route;
use App\Modules\V1\Utilities\enums\BillingCycle;
use App\Modules\V1\Platforms\Enums\SellingSystemEnum;

    Route::get('selling-systems', function() {
        return ApiResponse::success(SellingSystem::all()->map(function($system) {
            return [
                'id' => $system->id,
                'name' => $system->system->label(),
                'description' => $system->system->description(),
            ];
        }));
    });

    Route::get('billing-cycles', function() {
        return ApiResponse::success(BillingCycle::options());
    });

