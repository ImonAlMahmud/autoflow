<?php

namespace App\Enums;

enum ApprovalMode: string
{
    case Automatic = 'automatic';
    case Manual    = 'manual';

    public function label(): string
    {
        return match($this) {
            self::Automatic => 'Automatic',
            self::Manual    => 'Manual Approval',
        };
    }
}
