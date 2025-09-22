<?php

namespace App\Modules\Features\Models;

use Illuminate\Database\Eloquent\Model;
class FeatureTranslation extends Model
{

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description'
    ];
}
