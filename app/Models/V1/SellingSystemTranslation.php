<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class SellingSystemTranslation extends Model
{
    public $timestamps = false;

    public $fillable = [
        'name',
        'description'
    ];
}
