<?php

namespace App\Modules\V1\Initialization\Controllers;

use App\Facades\ApiResponse;
use App\Models\V1\SellingSystem;
use App\Models\V1\User;
use App\Modules\V1\Features\Services\FeatureService;
use App\Modules\V1\Initialization\Requests\IsDomainAvailableRequest;
use App\Modules\V1\Initialization\Requests\SavePlatformFeaturesRequest;
use App\Modules\V1\Initialization\Requests\SavingPlatformSystemsRequest;
use App\Modules\V1\Initialization\Resources\PlatformInitResource;
use App\Modules\V1\Initialization\Services\InitializePlatformService;
use App\Modules\V1\Utilities\Services\LocalizedCache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;

class PlatformInitializationController
{
    private LocalizedCache $sharedCache;

    public function __construct(public InitializePlatformService $service)
    {
        $this->sharedCache = LocalizedCache::make(prefix: 'features', tag: 'features');
    }

    public function getInitData(FeatureService $features)
    {
        $user = Auth::user();
        if (!is_null($user->platform)) {
            throw new UnauthorizedException(__('messages.not_authorized'));
        }

        $data['initData'] = $this->service->getPlatformInitData($user);

        $data['features'] = $this->sharedCache->rememberForever(
            key: 'active',
            callback: fn() => $features->getAll(true) // لو بيرجع مترجم حسب locale يبقى تمام
        );

        $data['selling_systems'] = $this->sharedCache->rememberForever(
            key: 'selling_systems',
            callback: fn() => SellingSystem::all()->map(function ($system) {
                return [
                    'id' => $system->id,
                    'name' => $system->system->label(),
                    'description' => $system->system->description(),
                ];
            })
        );

        return ApiResponse::success(new PlatformInitResource($data));
    }

    public function initFeatures(SavePlatformFeaturesRequest $request)
    {
        $platformFeatures = $request->validated();
        $this->service->storeOrUpdatePlatformFeatures($platformFeatures['features'], Auth::id());
        return ApiResponse::message('success');
    }

    public function initPlatformSystems(SavingPlatformSystemsRequest $request)
    {
        $user = Auth::user();
        if($this->initializationStep($user) < 1 )
            return ApiResponse::forbidden();

        $platformSystems = $request->validated();
        $features =  $this->service->UpdatePlatformSystems(
            $platformSystems,
            $user
        );
        return ApiResponse::success($features);
    }

    public function initPlatformDomain(IsDomainAvailableRequest $request)
    {
        $user = Auth::user();

        if($this->initializationStep($user) < 2)
            return ApiResponse::forbidden();

        $platformDomain = $request->validated();
        $domain = $this->service->UpdatePlatformDomain(
            $platformDomain['domain'],
            $user
        );
        return ApiResponse::success($domain);
    }


    public function isDomainAvailable(IsDomainAvailableRequest $request)
    {
        return ApiResponse::message(__('apiMessages.available'));
    }

    public function initializationStep(User $user)
    {
        return $user->platformInitialization?->step ?? 0;
    }

}
