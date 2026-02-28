<?php

namespace App\Modules\V1\Platforms\Models;

use App\Models\V1\SellingSystem;
use App\Models\V1\User;
use App\Modules\V1\Dashboard\Admins\Models\Admin;
use App\Modules\V1\Subscriptions\Models\Subscription;
use App\Modules\V1\Themes\Models\Theme;
use App\Modules\V1\Utilities\enums\SellingSystemEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class Platform extends Model
{
    use HasRoles;

    protected $guard_name = 'sanctum';

    protected $fillable = [
        'user_id',
        'domain',
        'started_at',
        'renew_at',
        'cost',
        'status'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
