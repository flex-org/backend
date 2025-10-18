<?php
namespace App\Traits\V1;

use App\Models\V1\Scopes\DomainScope;


trait BelongsToDomain
{
    protected static function bootBelongsToDomain()
    {
        static::addGlobalScope(new DomainScope);

        static::creating(function ($model) {
            if(config('platform.domain'))
                $model->domain = config('platform.domain');
        });
    }
}
