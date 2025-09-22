<?php

namespace App\Modules\Plans\Models;

use App\Modules\Plans\Enums\PlanType;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use App\Modules\Subscriptions\Models\Subscription;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use Translatable;

    protected $fillable = [
        'active',
        'storage', 
        'capacity',
        'type'
    ];

    public $translatedAttributes = [
        'description',
        'points'
    ];

    public $casts = [
        'type' => PlanType::class
    ];

    public function prices(): HasMany
    {
        return $this->hasMany(PlanPrice::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}
