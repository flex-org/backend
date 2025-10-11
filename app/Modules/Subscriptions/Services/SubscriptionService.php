<?php 
namespace App\Modules\Subscriptions\Services;

use App\Modules\Plans\Enums\PlanType;
use App\Modules\Features\Models\Feature;
use App\Modules\Subscriptions\Models\Subscription;
use App\Modules\Subscriptions\Enums\SubscriptionStatus;

class SubscriptionService
{

    public function subscripe($platform, $months, $featuresIds)
    {
        $features = Feature::whereIn('id', $featuresIds)->get();
        $price = $features->sum('price') * $months;

        $subscription = $this->createSubscription($price, $platform, $months);

        $this->assignPlatformFeatures(
            $platform,
            $subscription,
            $features
        );

        return $subscription;
    }

    public function createSubscription($price, $platform, $months)
    {
        return Subscription::create([
            'platform_id'     => $platform->id,
            'started_at'      => now()->addDays(3),
            'renew_at'        => now()->addMonth($months),
            'duration_months' => $months,
            'price'           => $price,
            'status'          => SubscriptionStatus::FREETRIAL,
        ]);
    }

    public function assignPlatformFeatures($platform, $subscription, $features)
    {
        $subscription->features()->attach(
            $features->pluck('id')
            ->mapWithKeys(fn ($id) => [
                $id => ['price' => $features->firstWhere('id', $id)->price]
            ])
        );

        $permissions = $features->map(fn($feature) =>
             'feature-' . $feature->id
        )->toArray();

        $platform->givePermissionTo($permissions);
    }
}