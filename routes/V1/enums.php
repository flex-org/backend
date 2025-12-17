<?php

use App\Facades\ApiResponse;
use App\Models\V1\SellingSystem;
use Illuminate\Support\Facades\Route;
use App\Modules\V1\Utilities\enums\BillingCycle;
use App\Modules\V1\Platforms\Enums\PlatformSellingSystem;

    Route::get('selling-systems', function() {
        return ApiResponse::success(SellingSystem::all());
    });

    Route::get('billing-cycles', function() {
        return ApiResponse::success(BillingCycle::options());
    });


    Route::get('selling-systems/add', function() {
        $systems = [
            [
                'translations' => [
                    'en' => [
                        'name' => 'Categories',
                        'description' => 'Sell by categories (includes all courses within the category)',
                    ],
                    'ar' => [
                        'name' => 'الأقسام',
                        'description' => 'البيع بالقسم (يشمل جمسع الكورسات داخل القسم)',
                    ],
                ],
            ],
            [
                'translations' => [
                    'en' => [
                        'name' => 'Courses',
                        'description' => 'Sell by courses (includes all sessions within the course)',
                    ],
                    'ar' => [
                        'name' => 'الكورسات',
                        'description' => 'البيع بالكورس (يشمل جميع الحلسات داخل الكورس)',
                    ],
                ],
            ],
            [
                'translations' => [
                    'en' => [
                        'name' => 'Sessions',
                        'description' => 'Sell by sessions',
                    ],
                    'ar' => [
                        'name' => 'الجلسات',
                        'description' => 'البيع بالجلسة المنفردة',
                    ],
                ],
            ],
        ];
        foreach ($systems as $systemData) {
            $system = new SellingSystem();
            $system->fill($systemData);
            $system->save();
        }
        return ApiResponse::success('Selling systems added successfully');
    });
