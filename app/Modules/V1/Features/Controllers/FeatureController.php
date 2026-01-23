<?php

namespace App\Modules\V1\Features\Controllers;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Modules\V1\Features\Services\FeatureService;
use App\Modules\V1\Features\Resources\FeatureResource;
use App\Modules\V1\Features\Requests\FeatureStoreRequest;
use App\Modules\V1\Features\Requests\FeatureUpdateRequest;

class FeatureController extends Controller
{
    public function __construct(public FeatureService $service)
    {

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $features = Cache::rememberForever('features', function () {
            return $this->service->getAll(true);
        });
        return ApiResponse::success(FeatureResource::collection($features));
    }

    public function getActiveFeatures()
    {
        $features = Cache::rememberForever('activeFeatures', function () {
            return $this->service->getAll(true);
        });
        return ApiResponse::success(FeatureResource::collection($features));
    }

    public function getDynamicFeatures()
    {
        $dynamicfeatures = Cache::rememberForever('dynamicFeatures', function () {
            return $this->service->getDynamic();
        });
        return ApiResponse::success($dynamicfeatures);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FeatureStoreRequest $request)
    {
        $this->service->create($request->validated());
        $this->updateCache();
        return ApiResponse::created();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $feature = $this->service->findById($id);
        return ApiResponse::success(new FeatureResource($feature));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FeatureUpdateRequest $request, string $id)
    {
        $feature = $this->service->findById($id);
        $this->service->update($feature, $request->validated());
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
        $features = $this->service->getAll();
        Cache::forever('features', $features);
        Cache::forever('activeFeatures', $features->where('active', true));
    }
}
