<?php

namespace App\Models\V1;

use App\Modules\V1\Platforms\Models\Platform;
use Illuminate\Database\Eloquent\Model;

class PlatformSellingSystem extends Model
{
    protected $fillable = ['platform_id', 'selling_system_id'];

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    public function sellingSystem()
    {
        return $this->belongsTo(SellingSystem::class);
    }
}
