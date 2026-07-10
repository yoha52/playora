<?php

namespace App\Enums;

enum BookingStatus: string
{
    case Confirmed = 'confirmed';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Confirmed => __('general.confirmed'),
            self::Completed => __('general.completed'),
            self::Cancelled => __('general.cancelled'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Confirmed => 'blue',
            self::Completed => 'green',
            self::Cancelled => 'red',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Confirmed => 'bg-neutral-secondary-medium border border-default-medium text-heading',
            self::Completed => 'bg-success-soft border border-success-subtle text-fg-success-strong',
            self::Cancelled => 'bg-danger-soft border border-danger-subtle text-fg-danger-strong',
        };
    }
}
