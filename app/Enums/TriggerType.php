<?php

namespace App\Enums;

enum TriggerType: string
{
    case Scheduled   = 'scheduled';
    case Manual      = 'manual';
    case Retry       = 'retry';
    case Regenerate  = 'regenerate';

    public function label(): string
    {
        return match($this) {
            self::Scheduled  => 'Scheduled',
            self::Manual     => 'Manual',
            self::Retry      => 'Retry',
            self::Regenerate => 'Regenerate',
        };
    }
}
