<?php

namespace App\Modules\Features\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use App\Modules\Features\Enums\FeatureType;
use App\Modules\Subscriptions\Models\Subscription;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Feature extends Model
{
    use Translatable;

    protected $fillable = [
        'icon',
        'price',
        'active',
    ];

    public $translatedAttributes = [
        'name',
        'description'
    ];
}
