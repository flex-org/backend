<?php
namespace App\Modules\Plans\Enums;

enum PlanBillingCycle: string
{
    case MONTHLY = 'monthly';
    case YEARLY = 'yearly';

    public function label(): string
    {
        return __("enums/static_keys.billing_cycle.{$this->value}");
    }

    public static function options(): array
    {
        return array_map(fn ($type) => [
            'value' => $type->value,
            'label' => $type->label(),
        ], self::cases());
    }
}
