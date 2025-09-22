<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('themes')->insert([
            [
                'type'       => 'basic',
                'name'       => 'Default Light',
                'colors'     => json_encode([
                    'background' => '#ffffff',
                    'text'       => '#000000',
                    'primary'    => '#007bff',
                    'secondary'  => '#6c757d',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type'       => 'basic',
                'name'       => 'Dark Mode',
                'colors'     => json_encode([
                    'background' => '#121212',
                    'text'       => '#ffffff',
                    'primary'    => '#1e88e5',
                    'secondary'  => '#bb86fc',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type'       => 'basic',
                'name'       => 'Nature',
                'colors'     => json_encode([
                    'background' => '#e8f5e9',
                    'text'       => '#2e7d32',
                    'primary'    => '#388e3c',
                    'secondary'  => '#81c784',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type'       => 'pro',
                'name'       => 'Ocean Breeze',
                'colors'     => json_encode([
                    'background' => '#e0f7fa',
                    'text'       => '#01579b',
                    'primary'    => '#0288d1',
                    'secondary'  => '#4dd0e1',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type'       => 'pro',
                'name'       => 'Sunset Glow',
                'colors'     => json_encode([
                    'background' => '#fff3e0',
                    'text'       => '#e65100',
                    'primary'    => '#fb8c00',
                    'secondary'  => '#ffcc80',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type'       => 'pro',
                'name'       => 'Cyberpunk',
                'colors'     => json_encode([
                    'background' => '#0d0d0d',
                    'text'       => '#f5f5f5',
                    'primary'    => '#ff0090',
                    'secondary'  => '#00e5ff',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type'       => 'pro',
                'name'       => 'Pastel Dreams',
                'colors'     => json_encode([
                    'background' => '#f8bbd0',
                    'text'       => '#4a148c',
                    'primary'    => '#ba68c8',
                    'secondary'  => '#ce93d8',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
