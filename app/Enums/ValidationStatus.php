<?php

namespace App\Enums;

enum ValidationStatus: string
{
    case Pending = 'pending';
    case Passed  = 'passed';
    case Failed  = 'failed';
    case Skipped = 'skipped';

    public function label(): string
    {
        return match($this) {
            self::Pending => 'Pending',
            self::Passed  => 'Passed',
            self::Failed  => 'Failed',
            self::Skipped => 'Skipped',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Pending => 'gray',
            self::Passed  => 'green',
            self::Failed  => 'red',
            self::Skipped => 'amber',
        };
    }
}
