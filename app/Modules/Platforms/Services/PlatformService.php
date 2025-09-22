<?php 
namespace App\Modules\Platforms\Services;

use App\Modules\Dashboard\Admins\Models\Admin;
use App\Traits\HasTranslation;
use App\Modules\Plans\Models\Plan;
use Illuminate\Support\Facades\DB;
use App\Modules\Plans\Enums\PlanType;
use App\Modules\Features\Models\Feature;
use App\Modules\Platforms\Models\Platform;
use App\Modules\Subscriptions\Models\Subscription;
use App\Modules\Subscriptions\Enums\SubscriptionStatus;
use App\Modules\Subscriptions\Services\SubscriptionService;
use App\Modules\Themes\Models\Theme;

class PlatformService
{
    public function __construct(public SubscriptionService $subscriptionService) {}

    public function getAll($active = false)
    {
    }

    public function findById(int $id, $active = true)
    {
    }

    public static function domainExists($domain)
    {
        return Platform::where('domain', $domain)->exists();
    }

    public function create($platformData, $user)
    {
        return DB::transaction(function () use($platformData, $user){

            $plan = Plan::with('prices')->find($platformData['plan_id']);
            
            $platform = $this->storePltform(
                $user->id, 
                $platformData['domain'], 
                $plan->storage, 
                $plan->capacity,
                $platformData['selling_system'],
            );

            $this->subscriptionService->subscripe($plan, $platform, $platformData);
  
            return [
                'url' => $this->platformUrl($platformData['domain']),
                'token' => $this->createPlatformOwner($platformData['domain'], $user)
            ];
        });
    }

    public function storePltform($user_id, $domain, $storage, $capacity, $selling_system) 
    {
        return Platform::create([
            'user_id' => $user_id,  
            'theme_id' => 1, 
            'domain' => $domain,  
            'storage' => $storage,  
            'capacity' => $capacity, 
            'selling_system' => $selling_system,
        ]);
    }

    public function update($plan, $planData)
    {
    }

    public function toggleActive($plan)
    {
    }

    public function delete($plan)
    {
    }

    public function platformUrl($domain)
    {
        return  $domain.'.'.env('FROTN_APP_URL')."/dashboard/apperance";
    }

    private function createPlatformOwner($domain, $user)
    {
        $admin = Admin::create([
            'domain' => $domain,
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
            'phone' => $user->phone,
        ]);
        $admin->assignRole('owner');
        return $admin->createToken('onwer', [$domain])->plainTextToken;
    }
}