<?php
namespace App\Modules\V1\Platforms\Services;

use App\Models\V1\User;
use App\Modules\V1\Platforms\Enums\PLatformStatus;
use App\Modules\V1\Platforms\Models\Platform;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PlatformService
{

    public function create($platformData, User $user): array
    {
        try {
            $response = Http::acceptJson()
                ->timeout(15)
                ->post(config('platforms.single_ed_system.create_tenant'), [
                    'domain' => $platformData['domain'],
                    'storage' => $platformData['storage'],
                    'capacity' => $platformData['capacity'],
                    'selling_systems' => collect($platformData['selling_systems'] ?? [])->pluck('id')->toArray(),
                    'features' => collect($platformData['features'] ?? [])->pluck('id')->toArray(),
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'password' => $user->password,
                ]);

            if (! $response->successful()) {
                Log::error('Tenant API HTTP error', [
                    'user_id' => $user->id,
                    'status_code' => $response->status(),
                    'response' => $response->body(),
                ]);

                return [
                    'success' => false,
                    'message' => 'HTTP request failed',
                    'status_code' => $response->status(),
                    'errors' => $response->json() ?? $response->body(),
                ];
            }

            $data = $response->json();

            if (! is_array($data)) {
                return [
                    'success' => false,
                    'message' => 'Invalid response returned from tenant service',
                    'status_code' => 500,
                ];
            }

            DB::beginTransaction();

            $platform = Platform::create([
                'domain' => $platformData['domain'],
                'user_id' => $user->id,
                'started_at' => now(),
                'renew_at' => now()->addDay(),
                'cost' => 0,
                'status' => PlatformStatus::FREE_TRIAL,
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => $data['message'] ?? 'Platform created successfully',
                'status_code' => 201,
                'data' => $data['data'],
            ];
        } catch (ConnectionException $e) {
            Log::error('Tenant API connection failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Cannot connect to tenant service',
                'status_code' => 503,
                'error' => $e->getMessage(),
            ];
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Unexpected platform creation error', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Unexpected error occurred while creating platform',
                'status_code' => 500,
                'error' => $e->getMessage(),
            ];
        }
    }

}
