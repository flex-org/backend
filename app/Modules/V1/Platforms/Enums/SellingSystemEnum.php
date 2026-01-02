<?php
namespace App\Modules\V1\Platforms\Enums;

enum SellingSystemEnum: string
{
    case CATEGORY    = 'ca';
    case COURSE    = 'co';
    case SESSION     = 's';
    case SUBSCRIPTION = 'ss';

    public function label(): string
    {
        return __("enums/static_keys.selling_system.{$this->value}.label");
    }

    public function description(): string
    {
        return __("enums/static_keys.selling_system.{$this->value}.description");
    }

    public static function options(): array
    {
        return array_map(fn ($case) => [
            'value' => $case->value,
            'label' => $case->label(),
        ], self::cases());
    }
}
