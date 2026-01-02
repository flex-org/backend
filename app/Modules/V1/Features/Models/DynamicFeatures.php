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

    public function quantityPrice($quantity)
    {
        return $this->price * max(1, $quantity / $this->quantity);
    }
}
