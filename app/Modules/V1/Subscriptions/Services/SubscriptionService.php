<?php 
namespace App\Modules\V1\Subscriptions\Services;

use App\Modules\V1\Features\Models\Feature;
use App\Modules\V1\Features\Models\DynamicFeatures;
use App\Modules\V1\Subscriptions\Models\Subscription;
use App\Modules\V1\Features\Enums\DynamicFeaturesValue;
use App\Modules\V1\Subscriptions\Enums\SubscriptionStatus;

class SubscriptionService
{

    public function subscripe($platform, $months, $platformData)
    {
        $features = Feature::whereIn('id', $platformData['features'])->get();
        $price = $this->subscriptionPriceCalculation($months, $features, $platformData);

        $subscription = $this->createSubscription($price, $platform, $months);

        $this->assignPlatformFeatures(
            $platform,
            $subscription,
            $features
        );

        return $subscription;
    }

    public function subscriptionPriceCalculation($months, $features, $platformData)
    {
        $featurePrice = $features->sum('price') * $months;
        $dynamicFeatures = DynamicFeatures::whereIn('name', DynamicFeaturesValue::values())->get();

        $dynamicFeaturePrice = $dynamicFeatures->filter(function ($dynamicFeature) use ($platformData) {
            return isset($platformData[$dynamicFeature->name]);
        })->sum(function ($dynamicFeature) use ($platformData) {
            return $dynamicFeature->quantityPrice($platformData[$dynamicFeature->name]);
        });

        return $featurePrice + $dynamicFeaturePrice;
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