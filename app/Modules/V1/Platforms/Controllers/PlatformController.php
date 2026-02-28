<?php

namespace App\Modules\V1\Platforms\Controllers;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\V1\Initialization\Services\InitializePlatformService;
use App\Modules\V1\Platforms\Services\PlatformService;
use Illuminate\Support\Facades\Auth;

class PlatformController extends Controller
{
    public function __construct(public PlatformService $service)
    {
    }

    /**
     *
     * Store a newly created resource in storage.
     */
    public function store(InitializePlatformService $initializer)
    {
        $platformInitData = $initializer->getPlatformInitData(Auth::user());
        $platformResponse = $this->service->create($platformInitData, Auth::user());
        $initializer->delete(Auth::user());
        return $platformResponse;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    }

}
