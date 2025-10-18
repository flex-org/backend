<?php

namespace Database\Seeders;

use App\Modules\Features\Models\DynamicFeatures;
use Illuminate\Database\Seeder;
use App\Modules\Features\Models\Feature;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Permission;

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
