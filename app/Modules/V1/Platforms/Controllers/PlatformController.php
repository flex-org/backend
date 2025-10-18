<?php

namespace App\Modules\V1\Platforms\Controllers;

use App\Facades\ApiResponse;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Modules\V1\Utilities\enums\BillingCycle;
use App\Modules\V1\Platforms\Services\PlatformService;
use App\Modules\V1\Platforms\Enums\PlatformSellingSystem;
use App\Modules\V1\Features\Requests\FeatureUpdateRequest;
use App\Modules\V1\Platforms\Requests\PlatformStoreRequest;
use App\Modules\V1\Subscriptions\Services\SubscriptionService;
use Illuminate\Http\Request;
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
     * Store a newly created resource in storage.
     */
    public function store(PlatformStoreRequest $request, SubscriptionService $subscriptionService)
    {
        if (Auth::user()->platform()) {
            return ApiResponse::message(
                __('apiMessages.platform.userHasPlatform'), 
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $platformData = $request->validated();
        $monthes = BillingCycle::From($platformData['billing_cycle'])->monthes();

        DB::transaction(function () use ($platformData, $monthes, $subscriptionService) {
            
            $platform = $this->service->create($platformData, Auth::id());
            
            $subscriptionService->subscripe(
                $platform, 
                $monthes, 
                $platformData
            );

        });
                    
        return ApiResponse::created(data: [
                'dashboard' => $this->service->platformUrl($platformData['domain'])
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

    public function isDomainAvailable(Request $request)
    {
        $request->validate([
            'domain' => 'required|string|max:100'
        ]);

        return match(PlatformService::domainExists($request['domain'])) {
            true => ApiResponse::message(__('apiMessages.notavailable'), Response::HTTP_UNPROCESSABLE_ENTITY),
            false => ApiResponse::message(__('apiMessages.available'))
        };
    }

}
