<?php

namespace App\Modules\Platforms\Models;

use App\Models\User;
use App\Modules\Themes\Models\Theme;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Dashboard\Admins\Models\Admin;
use App\Modules\Subscriptions\Models\Subscription;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\Platforms\Enums\PlatformSellingSystem;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function themes(): BelongsToMany
    {
        return $this->belongsToMany(Theme::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($platform) {
            $user = Auth::user();

            $platform->themes()->attach(
                Theme::whereNull('price')->pluck('id')
            );
            
            Admin::create([
                'domain' => $platform->domain,
                'name' => $user->name,
                'email' => $user->email,
                'password' => $user->password,
                'phone' => $user->phone,
            ])->assignRole('owner');
        });
    }
}
