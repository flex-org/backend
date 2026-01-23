<?php

namespace App\Modules\V1\Platforms\Controllers;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\V1\User;
use App\Modules\V1\Platforms\Requests\Initialization\IsDomainAvailableRequest;
use App\Modules\V1\Platforms\Requests\Initialization\SavePlatformFeaturesRequest;
use App\Modules\V1\Platforms\Requests\Initialization\SavingPlatformSystemsRequest;
use App\Modules\V1\Platforms\Services\InitializePlatformService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class PlatformInitializationController extends Controller
{
    public function __construct(public InitializePlatformService $service) {}

    public function getInitData()
    {
        if(is_null(Auth::user()->platformInitialization))
            throw new AccessDeniedException(__('messages.not_authorized'));
        $initData =  $this->service->getPlatformInitData(Auth::user());
        return ApiResponse::success($initData);
    }
    public function isDomainAvailable(IsDomainAvailableRequest $request)
    {
        return ApiResponse::message(__('apiMessages.available'));
    }

    public function initFeatures(SavePlatformFeaturesRequest $request)
    {
        $platformFeatures = $request->validated();
        $features =  $this->service->storeOrUpdatePlatformFeatures($platformFeatures['features'], Auth::id());
        return ApiResponse::success($features);
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
