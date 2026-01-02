<?php

namespace Database\Seeders;

use App\Modules\V1\Themes\Models\Theme;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ThemeSeeder extends Seeder
{
    public function run(): void
    {
        $themes = [
            [
                'price'   => null,
                'name'   => 'Default Light',
                'color'  => '#007bff',
                'colors' => json_encode([
                    'background' => '#ffffff',
                    'text'       => '#000000',
                    'primary'    => '#007bff',
                    'secondary'  => '#6c757d',
                ]),
            ],
            [
                'price'   => null,
                'name'   => 'Dark Mode',
                'color'  => '#1e88e5',
                'colors' => json_encode([
                    'background' => '#121212',
                    'text'       => '#ffffff',
                    'primary'    => '#1e88e5',
                    'secondary'  => '#bb86fc',
                ]),
            ],
            [
                'price'   => null,
                'name'   => 'Nature',
                'color'  => '#388e3c',
                'colors' => json_encode([
                    'background' => '#e8f5e9',
                    'text'       => '#2e7d32',
                    'primary'    => '#388e3c',
                    'secondary'  => '#81c784',
                ]),
            ],
            [
                'price'   => 100,
                'name'   => 'Ocean Breeze',
                'color'  => '#0288d1',
                'colors' => json_encode([
                    'background' => '#e0f7fa',
                    'text'       => '#01579b',
                    'primary'    => '#0288d1',
                    'secondary'  => '#4dd0e1',
                ]),
            ],
            [
                'price'   => 100,
                'name'   => 'Sunset Glow',
                'color'  => '#fb8c00',
                'colors' => json_encode([
                    'background' => '#fff3e0',
                    'text'       => '#e65100',
                    'primary'    => '#fb8c00',
                    'secondary'  => '#ffcc80',
                ]),
            ],
            [
                'price'   => 100,
                'name'   => 'Cyberpunk',
                'color'  => '#ff0090',
                'colors' => json_encode([
                    'background' => '#0d0d0d',
                    'text'       => '#f5f5f5',
                    'primary'    => '#ff0090',
                    'secondary'  => '#00e5ff',
                ]),
            ],
            [
                'price'   => 100,
                'name'   => 'Pastel Dreams',
                'color'  => '#ba68c8',
                'colors' => json_encode([
                    'background' => '#f8bbd0',
                    'text'       => '#4a148c',
                    'primary'    => '#ba68c8',
                    'secondary'  => '#ce93d8',
                ]),
            ],
            // ثيمات جديدة (جمرا – برتقالي – أزرق)
            [
                'price'   => 100,
                'name'   => 'Amber Blaze',
                'color'  => '#ff6f00',
                'colors' => json_encode([
                    'background' => '#fff8e1',
                    'text'       => '#ff6f00',
                    'primary'    => '#ff8f00',
                    'secondary'  => '#ffd54f',
                ]),
            ],
            [
                'price'   => 100,
                'name'   => 'Blue Horizon',
                'color'  => '#1976d2',
                'colors' => json_encode([
                    'background' => '#e3f2fd',
                    'text'       => '#0d47a1',
                    'primary'    => '#1976d2',
                    'secondary'  => '#64b5f6',
                ]),
            ],
            [
                'price'   => 100,
                'name'   => 'Fire Ember',
                'color'  => '#d32f2f',
                'colors' => json_encode([
                    'background' => '#ffebee',
                    'text'       => '#b71c1c',
                    'primary'    => '#d32f2f',
                    'secondary'  => '#ef9a9a',
                ]),
            ],
        ];

        Theme::insert($themes);
    }
}
