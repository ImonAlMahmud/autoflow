<?php

namespace App\Enums;

enum GitAuthMethod: string
{
    case HttpsToken = 'https_token';
    case Ssh        = 'ssh';

    public function label(): string
    {
        return match($this) {
            self::HttpsToken => 'HTTPS Personal Access Token',
            self::Ssh        => 'SSH Key',
        };
    }
}
