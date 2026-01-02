<?php

namespace Database\Seeders;

use App\Modules\V1\Features\Enums\DynamicFeaturesValue;
use App\Modules\V1\Features\Models\DynamicFeatures;
use Illuminate\Database\Seeder;

class DynamicFeatureSeeder extends Seeder
{
    public function run(): void
    {
        $dynamicFeatures = [
            [
                'name' => DynamicFeaturesValue::STORAGE,
                'quantity' => 1,
                'price' => 2,
            ],
            [
                'name' => DynamicFeaturesValue::CAPACITY,
                'quantity' => 10,
                'price' => 0.75,
            ],
            [
                'name' => DynamicFeaturesValue::MOBILEAPP,
                'quantity' => 1,
                'price' => 350,
            ],
        ];

        DynamicFeatures::insert($dynamicFeatures);
    }
}
