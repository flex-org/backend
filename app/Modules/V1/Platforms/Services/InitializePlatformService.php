<?php
namespace App\Modules\V1\Platforms\Services;

use App\Models\V1\SellingSystem;
use App\Modules\V1\Features\Models\Feature;
use App\Modules\V1\Platforms\Models\Platform;
use App\Modules\V1\Platforms\Models\PlatformInitialization;
use PHPUnit\Event\Telemetry\System;

class InitializePlatformService
{

    public function getPlatformInitData($user)
    {
        $platformInitData = $user->platformInitialization;

        return [
            'features' => $platformInitData?->features ?? [],
            'selling_systems' => $platformInitData?->selling_systems ?? [],
            'domain' => $platformInitData?->domain ?? '',
            'capacity' => (int) $platformInitData?->capacity ?? 100,
            'storage' => (int) $platformInitData?->storage ?? 20,
            'mobile_app' => (bool) $platformInitData?->mobile_app ?? false,
        ];
    }

    public function delete($user)
    {
        $platformInitData = $user->platformInitialization->delete();
    }

    public function storeOrUpdatePlatformFeatures($features, $userId)
    {
        $features = Feature::select([
            'id',
            'icon',
            'price',
            'active',
            'default'
        ])
        ->whereIn('id', $features)
        ->get();
         PlatformInitialization::UpdateOrCreate([
            'user_id' => $userId,
        ],[
            'features' => $features,
        ]);

        return $features;
    }

    public function UpdatePlatformDomain($domain, $user)
    {
        $data = ['domain' => $domain];
        $user->platformInitialization->update($data);
        return $data;
    }

    public function UpdatePlatformSystems($systems, $user)
    {
        $data = [
            'capacity' => (int) $systems['capacity'],
            'storage' => (int) $systems['storage'],
            'mobile_app' => (bool) $systems['mobile_app'],
            'selling_systems' => SellingSystem::select('id')->whereIn('id', $systems['selling_system'])->pluck('id')
        ];
        $user->platformInitialization->update($data);
        return $data;
    }

    static function domainExists($domain)
    {
        return Platform::where('domain', $domain)->exists();
    }

}
