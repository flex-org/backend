<?php
namespace App\Modules\Utilities\enums;

enum BillingCycle: string
{
    case MONTHLY = 'monthly';
    case YEARLY = 'yearly';
    case QUARTERLY = 'quarterly';

    public function label(): string
    {
        return __("enums/static_keys.selling_system.{$this->value}");
    }

    public static function options(): array
    {
        return array_map(fn ($type) => [
            'value' => $type->value,
            'label' => $type->label(),
        ], self::cases());
    }

    public function monthes(): int
    {
        return match($this) {
            self::MONTHLY => 1,
            self::QUARTERLY => 3,
            self::YEARLY => 12,
        };
    }
}