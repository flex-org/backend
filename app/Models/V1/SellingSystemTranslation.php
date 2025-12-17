<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class SellingSystemTranslation extends Model
{
    public $fillable = ['name', 'description'];
}
