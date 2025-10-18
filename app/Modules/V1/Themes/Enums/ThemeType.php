<?php
namespace App\Modules\V1\Themes\Enums;

enum ThemeType: string
{
    case BASIC = 'basic';
    case PRO = 'pro';

    public function label(): string
    {
        return __("enums/static_keys.plan_type.{$this->value}");
    }

    public static function options(): array
    {
        return array_map(fn ($type) => [
            'value' => $type->value,
            'label' => $type->label(),
        ], self::cases());
    }
}
