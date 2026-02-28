<?php
namespace App\Modules\V1\Initialization\Services;

use App\Models\V1\SellingSystem;
use App\Modules\V1\Features\Models\Feature;
use App\Modules\V1\Initialization\Models\PlatformInitialization;
use App\Modules\V1\Utilities\Services\LocalizedCache;
use Illuminate\Support\Facades\Auth;

class InitializePlatformService
{
    private LocalizedCache $userInitCache;

    public function __construct()
    {
        $this->userInitCache = LocalizedCache::make(prefix: 'init:user:' . Auth::id(), tag: 'init');
    }

    public function getPlatformInitData($user) : PlatformInitialization
    {
        return $this->userInitCache->rememberForever(
            key: 'data' ,
            callback: fn() => PlatformInitialization::with('features')
            ->firstOrCreate([
                'user_id' => $user->id
            ])
        );
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
        ->orWhere('default', true)
        ->pluck('id')
        ->toArray();

        $platformInit = PlatformInitialization::updateOrCreate([
            'user_id' => $userId,
        ],[
            'step' => 1,
        ]);
        $platformInit->features()->sync($features);
        $this->userInitCache->forgetAllLocales('data');
        return $platformInit;
    }

    public function UpdatePlatformSystems($systems, $user) : array
    {
        $data = [
            'capacity' => (int) $systems['capacity'],
            'storage' => (int) $systems['storage'],
            'mobile_app' => (bool) $systems['mobile_app'],
            'selling_systems' => SellingSystem::select('id','system')
                ->whereIn('id', $systems['selling_system'])
                ->get(),
            'step' => 2,
        ];
        $user->platformInitialization->update($data);
        $this->userInitCache->forgetAllLocales('data');
        return $data;
    }
    public function UpdatePlatformDomain($domain, $user) : array
    {
        $data = [
            'step' => 3,
            'domain' => $domain
        ];
        $user->platformInitialization->update($data);
        $this->userInitCache->forgetAllLocales('data');
        return $data;
    }

    public function delete($user)
    {
        $this->userInitCache->forgetAllLocales('data');
        return $user->platformInitialization->delete();
    }

}
