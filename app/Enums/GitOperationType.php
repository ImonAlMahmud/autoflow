<?php

namespace App\Enums;

enum GitOperationType: string
{
    case Clone  = 'clone';
    case Pull   = 'pull';
    case Fetch  = 'fetch';
    case Commit = 'commit';
    case Push   = 'push';
    case Status = 'status';

    public function label(): string
    {
        return match($this) {
            self::Clone  => 'Clone',
            self::Pull   => 'Pull',
            self::Fetch  => 'Fetch',
            self::Commit => 'Commit',
            self::Push   => 'Push',
            self::Status => 'Status Check',
        };
    }
}
