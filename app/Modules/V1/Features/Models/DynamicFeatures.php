<?php

namespace App\Modules\V1\Features\Models;

use Illuminate\Database\Eloquent\Model;

class DynamicFeatures extends Model
{
    protected $fillable = [
        'name',
        'quantity',
        'price',
    ];


    public function quantityPrice($value)
    {
        return $this->price * max(1, $value / $this->quantity);
    }
}
