<?php

namespace Database\Seeders;

use App\Models\V1\SellingSystem;
use App\Modules\V1\Platforms\Enums\SellingSystemEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class SellingSystemSeeder extends Seeder
{
    public function run(): void
    {
        SellingSystem::insert([
            [
                'system' => SellingSystemEnum::CATEGORY,
            ],
            [
                'system' => SellingSystemEnum::COURSE,
            ],
            [
                'system' => SellingSystemEnum::SESSION,
            ],
            [
                'system' => SellingSystemEnum::SUBSCRIPTION,
            ]
        ]);
    }
}
