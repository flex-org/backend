<?php
namespace App\Modules\V1\Features\Enums;

enum DynamicFeaturesValue: string
{
    case STORAGE = 'storage';
    case CAPACITY = 'capacity';
    case MOBILE_APP = 'mobile_app';

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
