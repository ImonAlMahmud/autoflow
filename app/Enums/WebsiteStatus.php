<?php

namespace App\Enums;

enum WebsiteStatus: string
{
    case Active   = 'active';
    case Paused   = 'paused';
    case Disabled = 'disabled';

    public function label(): string
    {
        return match($this) {
            self::Active   => 'Active',
            self::Paused   => 'Paused',
            self::Disabled => 'Disabled',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Active   => 'green',
            self::Paused   => 'amber',
            self::Disabled => 'gray',
        };
    }
}
