<?php
namespace App\Modules\V1\Subscriptions\Enums;

enum SubscriptionStatus: string
{
    case ACTIVE = 'active';
    case EXPIRED = 'expired';
    case FREE_TRIAL = 'free_trial';
    case PENDING = 'pending';
    case DEACTIVATED = 'deactivated';

    public function label(): string
    {
        return __("enums/static_keys.subscription_status.{$this->value}");
    }

    public static function options(): array
    {
        return array_map(fn ($type) => [
            'value' => $type->value,
            'label' => $type->label(),
        ], self::cases());
    }
}
