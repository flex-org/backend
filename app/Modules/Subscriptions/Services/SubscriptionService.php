<?php 
namespace App\Modules\Subscriptions\Services;

use App\Modules\Plans\Enums\PlanType;
use App\Modules\Features\Models\Feature;
use App\Modules\Subscriptions\Models\Subscription;
use App\Modules\Subscriptions\Enums\SubscriptionStatus;

class SubscriptionService
{


    public function subscripe($plan, $platform, $platformData)
    {
        $pricing = $plan->prices()
                ->where('billing_cycle', $platformData['billing_cycle'])
                ->firstOrFail();

        $subscriptionData = match($plan->type) {
            PlanType::BASIC => $this->prepareBasicSubscriptionData($plan, $pricing),
            PlanType::PRO => $this->prepareProSubscriptionData($platformData['features'], $pricing),
        };

        $this->createSubscription(
            $platform,
            $pricing->months,
            $subscriptionData['price'],
            $plan->id,
            $subscriptionData['features']
        );  
    }

    public function prepareProSubscriptionData($featuresIds, $pricing)
    {
        $features = Feature::whereIn('id', $featuresIds)->get();
        $subscriptionPrice = max(0, $features->sum('price') - $pricing->discount);
        return ['features' => $features , 'price' => $subscriptionPrice];
    }

    public function prepareBasicSubscriptionData($plan, $pricing)
    {
        $features = $plan->features()->get();
        $subscriptionPrice = max(0, $pricing->price - $pricing->discount);
        return ['features' => $features , 'price' => $subscriptionPrice];
    }

    public function createSubscription($platform, $months, $price, $planId, $features)
    {
        $subscription = Subscription::create([
            'platform_id'     => $platform->id,
            'plan_id'         => $planId,
            'started_at'      => now()->addDays(7),
            'renew_at'        => now()->addMonth($months),
            'duration_months' => $months,
            'price'           => $price,
            'status'          => SubscriptionStatus::FREETRIAL,
        ]);
        $this->assignPlatformFeatures($platform, $subscription, $features);
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