<?php

namespace App\Modules\V1\Subscriptions\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\V1\Features\Models\Feature;
use App\Modules\V1\Platforms\Models\Platform;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\V1\Subscriptions\Enums\SubscriptionStatus;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subscription extends Model
{

    protected $fillable = [
        'platform_id',
        'started_at',
        'renew_at',
        'duration_months',
        'price',
        'status'
    ];

    protected $casts = [
        'started_at' => 'date',
        'renew_at' => 'date',
        'status' => SubscriptionStatus::class
    ];

    public function platform(): BelongsTo
    {
        return $this->belongsTo(Platform::class);
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(
            Feature::class,
            'subscription_features'
        )
        ->withPivot(['price'])
        ->withTimestamps();
    }
}

