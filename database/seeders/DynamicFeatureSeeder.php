<?php

namespace Database\Seeders;

use App\Modules\V1\Features\Models\DynamicFeatures;
use Illuminate\Database\Seeder;

class DynamicFeatureSeeder extends Seeder
{
    public function run(): void
    {
        $dynamicFeatures = [
            [
                'name' => 'storage',
                'quantity' => 100,
                'price' => 80,
            ],
            [
                'name' => 'capacity',
                'quantity' => 2000,
                'price' => 100,
            ],
        ];

        DynamicFeatures::insert($dynamicFeatures);
    }
}
