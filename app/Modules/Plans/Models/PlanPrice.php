<?php

namespace App\Modules\Plans\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanPrice extends Model
{
    
    protected $fillable = [
        'plan_id',
        'billing_cycle',
        'months',
        'price',
        'discount',
        'is_in_sale'
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
}

