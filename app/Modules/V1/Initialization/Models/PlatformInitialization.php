<?php

namespace App\Modules\V1\Initialization\Models;

use App\Models\V1\User;
use App\Modules\V1\Features\Models\Feature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PlatformInitialization extends Model
{
    protected $fillable = [
        'user_id',
        'step',
        'domain',
        'capacity',
        'storage',
        'selling_systems',
        'mobile_app'
    ];

    protected $casts = [
        'selling_systems' => 'array'
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function features() : BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 'platform_initial_features', 'platform_id', 'feature_id');
    }
}
