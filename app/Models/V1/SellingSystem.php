<?php

namespace App\Models\V1;

use App\Modules\V1\Platforms\Enums\SellingSystemEnum;
use Illuminate\Database\Eloquent\Model;

class SellingSystem extends Model
{
    protected $fillable = ['system'];
    protected $casts  = [
        'system' => SellingSystemEnum::class,
    ];
}
