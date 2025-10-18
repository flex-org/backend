<?php
namespace App\Modules\V1\Features\Enums;

enum DynamicFeaturesValue: string
{
    case STORAGE = 'storage';
    case CAPACITY = 'capacity';

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}