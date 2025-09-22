<?php

namespace App\Modules\Platforms\Models;

use App\Models\User;
use App\Modules\Themes\Models\Theme;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Subscriptions\Models\Subscription;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\Platforms\Enums\PlatformSellingSystem;

class Platform extends Model
{
    use HasRoles;

    protected $guard_name = 'sanctum';
    protected $fillable = [
        'user_id', 
        'theme_id', 
        'domain', 
        'storage', 
        'capacity',
        'selling_system'
    ];

    public $casts = [
        'selling_system' => PlatformSellingSystem::class
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}
