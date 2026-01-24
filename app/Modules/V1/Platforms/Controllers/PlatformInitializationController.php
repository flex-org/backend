<?php

namespace App\Modules\V1\Platforms\Controllers;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\V1\Features\Services\FeatureService;
use App\Modules\V1\Platforms\Requests\Initialization\IsDomainAvailableRequest;
use App\Modules\V1\Platforms\Requests\Initialization\SavePlatformFeaturesRequest;
use App\Modules\V1\Platforms\Requests\Initialization\SavingPlatformSystemsRequest;
use App\Modules\V1\Platforms\Resources\PlatformInitResource;
use App\Modules\V1\Platforms\Services\InitializePlatformService;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class PlatformInitializationController extends Controller
{
    public function __construct(
        public InitializePlatformService $service,
    ) {}

    public function getInitData(FeatureService $features)
    {
        $user = Auth::user();
        if(!is_null($user->platform))
            throw new AccessDeniedHttpException(__('messages.not_authorized'));
        $data['initData'] = $this->service->getPlatformInitData($user);
        $data['features'] = $features->getAll(true);
        return ApiResponse::success(
            new PlatformInitResource($data)
        );
    }
    public function isDomainAvailable(IsDomainAvailableRequest $request)
    {
        return ApiResponse::message(__('apiMessages.available'));
    }

    public function initFeatures(SavePlatformFeaturesRequest $request)
    {
        $platformFeatures = $request->validated();
        $features =  $this->service->storeOrUpdatePlatformFeatures($platformFeatures['features'], Auth::id());
        return ApiResponse::message('success');
    }

    public function initPlatformDomain(IsDomainAvailableRequest $request)
    {
        $platformDomain = $request->validated();
        $domain =  $this->service->UpdatePlatformDomain($platformDomain['domain'], Auth::user());
        return ApiResponse::success($domain);
    }

    public function initPlatformSystems(SavingPlatformSystemsRequest $request)
    {
        $platformSystems = $request->validated();
        $features =  $this->service->UpdatePlatformSystems($platformSystems, Auth::user());
        return ApiResponse::success($features);
    }

}
