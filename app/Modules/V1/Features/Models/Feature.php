<?php

namespace App\Modules\V1\Features\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class Feature extends Model
{
    use Translatable;

    protected $fillable = [
        'icon',
        'price',
        'active',
        'default'
    ];

    public $translatedAttributes = [
        'name',
        'description'
    ];
}
