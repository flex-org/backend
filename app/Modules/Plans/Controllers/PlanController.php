<?php

namespace App\Modules\Plans\Controllers;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\Plans\Requests\PlanStoreRequest;
use App\Modules\Plans\Requests\PlanUpdateRequest;
use Illuminate\Support\Facades\Cache;
use App\Modules\Plans\Services\PlanService;
use App\Modules\Plans\Resources\PlanResource;

class PlanController extends Controller
{
    public function __construct(public PlanService $service) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {        
        $plans = Cache::rememberForever('plans', function () {
            return $this->service->getAll();
        });
        return ApiResponse::success(PlanResource::collection($plans));
    }

    public function getActivePlans()
    {
        $plans = Cache::rememberForever('activePlans', function () {
            return $this->service->getAll(true);
        });
        return ApiResponse::success(PlanResource::collection($plans));
    }

    // /**
    //  * Store a newly created resource in storage.
    //  */
    // public function store(PlanStoreRequest $request)
    // {
    //     $this->service->create($request->validated());
    //     $this->updateCache();
    //     return ApiResponse::created();
    // }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $plan = $this->service->findById($id);
        return ApiResponse::success(new PlanResource($plan));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PlanUpdateRequest $request, string $id)
    {
        $plan = $this->service->findById($id);
        $this->service->update($plan, $request->validated());
        $this->updateCache();
        return ApiResponse::updated();
    }

    /**
     * Update the specified resource in storage.
     */
    public function activation(string $id)
    {
        $feature = $this->service->findById($id);
        $this->service->toggleActive($feature);
        $this->updateCache();
        return ApiResponse::updated();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $feature = $this->service->findById($id);
        $this->service->delete($feature);
        $this->updateCache();
        return ApiResponse::deleted();
    }

    public function updateCache()
    {
        $plans = $this->service->getAll();
        Cache::forever('plans', $plans);
        Cache::forever('activePlans', $plans->where('active', true));
    }
}
