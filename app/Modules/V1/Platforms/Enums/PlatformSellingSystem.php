<?php
namespace App\Modules\V1\Platforms\Enums;

enum PlatformSellingSystem: string
{
    case CACOS = 'cacos';
    case CACO  = 'caco';
    case CAS   = 'cas';
    case COS   = 'cos';
    case CA    = 'ca';
    case CO    = 'co';
    case S     = 's';

    public function label(): string
    {
        return __("enums/static_keys.selling_system.{$this->value}");
    }

    public static function options(): array
    {
        return array_map(fn ($type) => [
            'value' => $type->value,
            'label' => $type->label(),
        ], [self::CA, self::CO, self::S]);
    }
}
