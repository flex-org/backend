<?php

namespace App\Modules\Subscriptions\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Features\Models\Feature;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionFeature extends Model
{
    protected $fillable = [
        'subscription_id', 
        'feature_id', 
        'price'
    ];

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function feature(): BelongsTo
    {
        return $this->belongsTo(Feature::class);
    }
}

