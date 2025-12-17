<?php
namespace App\Modules\V1\Platforms\Services;

use App\Modules\V1\Platforms\Models\Platform;
use App\Modules\V1\Themes\Models\Theme;

class PlatformService
{
    static function domainExists($domain)
    {
        return Platform::where('domain', $domain)->exists();
    }

    function create($platformData, $user_id)
    {
        $platform = Platform::create([
            'user_id' => $user_id,
            'theme_id' => Theme::firstWhere('price', null)->id,
            'domain' => $platformData['domain'],
            'storage' => $platformData['storage'],
            'capacity' => $platformData['capacity'],
        ]);

        $platform->sellingSystems()->attach($platformData['selling_system']);

        return $platform;
    }

    function platformUrl($domain)
    {
        return  'https://'.'.'.$domain.'.'.env('FROTN_APP_URL')."/dashboard/apperance";
    }
}
