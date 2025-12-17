<?php

use App\Facades\ApiResponse;
use App\Models\V1\SellingSystem;
use Illuminate\Support\Facades\Route;
use App\Modules\V1\Utilities\enums\BillingCycle;
use App\Modules\V1\Platforms\Enums\PlatformSellingSystem;

    Route::get('selling-systems', function() {
        return ApiResponse::success(SellingSystem::all()->map(function($system) {
            return [
                'id' => $system->id,
                'name' => $system->name,
                'description' => $system->description,
            ];
        }));
    });

    Route::get('selling-systems/delete', function() {
        return SellingSystem::all()->each(function($system) {
            $system->delete();
        });
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
        foreach ($systems as $data) {
            $translations = Arr::pull($data, 'translations');

            $system = SellingSystem::create($data);

            foreach ($translations as $locale => $translation) {
                $system->translateOrNew($locale)->name = $translation['name'];
                $system->translateOrNew($locale)->description = $translation['description'];
            }
            $system->save();
        }
        return ApiResponse::success('Selling systems added successfully');
    });
