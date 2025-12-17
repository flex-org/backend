<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class SellingSystem extends Model
{
    use Translatable;
    public $translatedAttributes = ['name', 'description'];
}
