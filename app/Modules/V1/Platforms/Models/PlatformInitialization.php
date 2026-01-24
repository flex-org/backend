<?php

namespace App\Modules\V1\Platforms\Models;

use App\Models\V1\User;
use App\Modules\V1\Features\Models\Feature;
use Illuminate\Database\Eloquent\Model;

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'platform_initial_features', 'platform_id', 'feature_id');
    }
}
