<?php

namespace App\Modules\Themes\Models;

use App\Modules\Themes\Enums\ThemeType;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $fillable = [
        'name', 
        'colors',
        'type'
    ];

    protected $casts = [
        'colors' => 'array',
        'type' => ThemeType::class
    ];
}
