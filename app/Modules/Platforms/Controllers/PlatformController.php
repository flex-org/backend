<?php

namespace App\Modules\Platforms\Controllers;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\Platforms\Services\PlatformService;
use App\Modules\Features\Requests\FeatureUpdateRequest;
use App\Modules\Platforms\Enums\PlatformSellingSystem;
use App\Modules\Platforms\Requests\PlatformStoreRequest;
use App\Modules\Platforms\Requests\AvailableDomainRequest;
use Illuminate\Http\Response;

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
    public function store(PlatformStoreRequest $request)
    {
        $platformData = $this->service->create($request->validated(), $request->user());
        return ApiResponse::created($platformData);
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

    public function isDomainAvailable($domain)
    {
        return match(PlatformService::domainExists($domain)) {
            true => ApiResponse::message(__('apiMessages.notavailable'), Response::HTTP_UNPROCESSABLE_ENTITY),
            false => ApiResponse::message(__('apiMessages.available'))
        };
    }

    public function sellingSystems()
    {
        return ApiResponse::success(PlatformSellingSystem::options());
    }

}
