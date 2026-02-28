<?php

namespace App\Modules\V1\Features\Controllers;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\V1\Features\Requests\FeatureCreateRequest;
use App\Modules\V1\Features\Requests\FeatureUpdateRequest;
use App\Modules\V1\Features\Resources\FeatureResource;
use App\Modules\V1\Features\Resources\PlatformInitResource;
use App\Modules\V1\Features\Services\FeatureService;
use App\Modules\V1\Utilities\Services\LocalizedCache;

class FeatureController extends Controller
{
    private LocalizedCache $cache;
    public function __construct(public FeatureService $service)
    {
        $this->cache = LocalizedCache::make(prefix: 'features', tag: 'features');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $features =  $this->service->getAll();
        return ApiResponse::success(FeatureResource::collection($features));
    }

    public function getActiveFeatures()
    {
        $features = $this->service->getAll(true);
        return ApiResponse::success(FeatureResource::collection($features));
    }

    public function getDynamicFeatures()
    {
        $dynamicFeatures =  $this->service->getDynamic();

        $data = $dynamicFeatures->mapWithKeys(function ($feature) {
            return [
                $feature->name => [
                    'quantity' => 1,
                    'price' => $feature->price,
                ]
            ];
        });

        return ApiResponse::success($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $feature = $this->service->findById($id);
        return ApiResponse::success(new FeatureResource($feature));
    }
    public function store(FeatureCreateRequest $request)
    {
        $this->service->create($request->validated());
        return ApiResponse::created();
    }

    public function update(FeatureUpdateRequest $request, string $id)
    {
        $feature = $this->service->findById($id);
        $this->service->update($feature, $request->validated());
        return ApiResponse::updated();
    }

    public function activation(string $id)
    {
        $feature = $this->service->findById($id);
        $this->service->toggleActive($feature);
        return ApiResponse::updated();
    }

    public function destroy(string $id)
    {
        $feature = $this->service->findById($id);
        $this->service->delete($feature);
        return ApiResponse::deleted();
    }
}
