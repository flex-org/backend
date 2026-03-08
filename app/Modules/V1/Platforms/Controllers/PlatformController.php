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
        $user = Auth::user();
        $platformInitData = $initializer->getPlatformInitData($user);

        $result = $this->service->create($platformInitData, $user);

        if ($result['success']) {
            $initializer->delete($user);
            return ApiResponse::created($result['data']);
        }
        return ApiResponse::message($result['message'], $result['status_code']);
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
