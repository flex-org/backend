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
        return PlatformInitialization::with('features')
            ->firstOrCreate(
                ['user_id' => $user->id],
                ['step' => 0]
            );
    }

    public function delete($user)
    {
        $platformInitData = $user->platformInitialization->delete();
    }

    public function storeOrUpdatePlatformFeatures($featuresIds, $userId)
    {
        $features = Feature::select([
            'id',
            'icon',
            'price',
            'active',
            'default'
        ])
        ->where('active', true)
        ->whereIn('id', $featuresIds)
        ->pluck('id')
        ->toArray();

        $platform = PlatformInitialization::firstOrCreate([
            'user_id' => $userId,
        ],[
            'step' => 1,
        ]);
        $platform->features()->sync($features);
        return $platform;
    }

    public function UpdatePlatformDomain($domain, $user)
    {
        $data = [
            'step' => 3,
            'domain' => $domain
        ];
        $user->platformInitialization->update($data);
        return $data;
    }

    public function UpdatePlatformSystems($systems, $user)
    {
        $data = [
            'step' => 2,
            'capacity' => (int) $systems['capacity'],
            'storage' => (int) $systems['storage'],
            'mobile_app' => (bool) $systems['mobile_app'],
            'selling_systems' => SellingSystem::select('id','system')->whereIn('id', $systems['selling_system'])->get()
        ];
        $user->platformInitialization->update($data);
        return $data;
    }

    static function domainExists($domain)
    {
        return Platform::where('domain', $domain)->exists();
    }

}
