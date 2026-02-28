<?php

use App\Facades\ApiResponse;
use App\Models\V1\SellingSystem;
use Illuminate\Support\Facades\Route;

Route::get('selling-systems', function() {
        return ApiResponse::success(SellingSystem::all()->map(function($system) {
            return [
                'id' => $system->id,
                'name' => $system->system->label(),
                'description' => $system->system->description(),
            ];
        }));
    });

