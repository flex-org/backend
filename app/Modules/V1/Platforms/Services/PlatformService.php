<?php
namespace App\Modules\V1\Platforms\Services;

use App\Modules\V1\Platforms\Models\Platform;
use App\Modules\V1\Themes\Models\Theme;

class PlatformService
{
    function create($platformData, $user_id)
    {
        $platform = Platform::create([
            'user_id' => $user_id,
            'theme_id' => Theme::firstWhere('price', null)->id,
            'domain' => $platformData['domain'],
            'storage' => $platformData['storage'],
            'capacity' => $platformData['capacity'],
        ]);

        $platform->sellingSystems()->attach(collect($platformData['selling_systems'])->pluck('id')->toArray());

        return $platform;
    }
    function platformUrl($domain)
    {
        return  'https://'.$domain.'.'.env('FRONTEND_URL')."/dashboard/apperance";
    }
}
