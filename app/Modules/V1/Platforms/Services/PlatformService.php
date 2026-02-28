<?php
namespace App\Modules\V1\Platforms\Services;

use App\Modules\V1\Platforms\Enums\PLatformStatus;
use App\Modules\V1\Platforms\Models\Platform;
use Illuminate\Support\Facades\Http;

class PlatformService
{

    function create($platformData, $user)
    {
        Platform::create([
            'domain' => $platformData['domain'],
            'user_id' => $user->id,
            'started_at' => now(),
            'renew_at' => now()->addDay(),
            'cost' => 0,
            'status' => PLatformStatus::FREE_TRIAL,
        ]);

        $response = Http::accept('application/json')
            ->post(
                config('platforms.single_ed_system.create_tenant'),
                [
                    'domain' => $platformData['domain'],
                    'storage' => $platformData['storage'],
                    'capacity' => $platformData['capacity'],
                    'selling_systems' => collect($platformData['selling_systems'])->pluck('id')->toArray(),
                    'features' => collect($platformData['features'])->pluck('id')->toArray(),
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'password' => $user->password,
                ]
            );

        return $response->body();
    }

}
