<?php

namespace App\Modules\V1\Platforms\Models;

use App\Models\V1\User;
use Illuminate\Database\Eloquent\Model;

class PlatformInitialization extends Model
{
    protected $fillable = [
        'user_id',
        'features',
        'domain',
        'capacity',
        'storage',
        'selling_systems',
        'mobile_app'
    ];

    protected $casts = [
        'features' => 'array',
        'selling_systems' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
