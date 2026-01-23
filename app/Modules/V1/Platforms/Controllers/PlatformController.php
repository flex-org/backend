<?php

namespace App\Modules\V1\Platforms\Controllers;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\V1\Features\Requests\FeatureUpdateRequest;
use App\Modules\V1\Platforms\Requests\Initialization\SavePlatformFeaturesRequest;
use App\Modules\V1\Platforms\Requests\PlatformStoreRequest;
use App\Modules\V1\Platforms\Services\InitializePlatformService;
use App\Modules\V1\Platforms\Services\PlatformService;
use App\Modules\V1\Subscriptions\Services\SubscriptionService;
use App\Modules\V1\Utilities\enums\BillingCycle;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PlatformController extends Controller
{
    public function __construct(public PlatformService $service) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    /**
     *
     * Store a newly created resource in storage.
     */
    public function store(PlatformStoreRequest $request, SubscriptionService $subscriptionService, InitializePlatformService $initializer)
    {
        $platformData = $request->validated();
        $platformInitData = $initializer->getPlatformInitData(Auth::user());
        $months = BillingCycle::From($platformData['billing_cycle'])->monthes();

        DB::transaction(function () use ($months, $subscriptionService, $platformInitData, $initializer) {
            $platform = $this->service->create($platformInitData, Auth::id());
            $subscriptionService->subscribe(
                $platform,
                $months,
                $platformInitData
            );
            $initializer->delete(Auth::user());
        });

        return ApiResponse::created(data: [
                'dashboard' => $this->service->platformUrl($platformInitData['domain'])
            ],
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FeatureUpdateRequest $request, string $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    }

//    public function isDomainAvailable(Request $request)
//    {
//        $request->validate([
//            'domain' => 'required|string|max:100'
//        ]);
//
//        return match(PlatformService::domainExists($request['domain'])) {
//            true => ApiResponse::message(__('apiMessages.notavailable'), Response::HTTP_UNPROCESSABLE_ENTITY),
//            false => ApiResponse::message(__('apiMessages.available'))
//        };
//    }


}
