<?php 
namespace App\Modules\Platforms\Services;

use App\Modules\Dashboard\Admins\Models\Admin;
use App\Modules\Platforms\Models\Platform;
use App\Modules\Themes\Models\Theme;

class PlatformService
{
    static function domainExists($domain)
    {
        return Platform::where('domain', $domain)->exists();
    }

    function create($platformData, $user_id)
    {
        return Platform::create([
            'user_id' => $user_id,  
            'theme_id' => Theme::firstWhere('price', null)->id,
            'domain' => $platformData['domain'],  
            'selling_system' => $platformData['selling_system'],
            'storage' => 100,
            'capacity' => 1000,
        ]);
    }

    function platformUrl($domain)
    {
        return  'https://'.'.'.$domain.'.'.env('FROTN_APP_URL')."/dashboard/apperance";
    }
}