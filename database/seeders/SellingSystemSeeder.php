<?php

namespace Database\Seeders;

use App\Models\V1\SellingSystem;
use App\Modules\V1\Utilities\enums\SellingSystemEnum;
use Illuminate\Database\Seeder;

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
