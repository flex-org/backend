<?php

namespace App\Modules\Plans\Models;

use Illuminate\Database\Eloquent\Model;

class PlanTranslation extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'description',
        'points'
    ];

    protected $casts = [
        'points' => 'array'
    ];
}
