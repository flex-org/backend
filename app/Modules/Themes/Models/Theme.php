<?php

namespace App\Modules\Themes\Models;

use App\Modules\Themes\Enums\ThemeType;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $fillable = [
        'name', 
        'color',
        'colors',
        'price'
    ];

    protected $casts = [
        'colors' => 'array',
    ];
}
