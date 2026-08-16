<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SystemSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'is_sensitive',
    ];

    protected function casts(): array
    {
        return [
            'is_sensitive' => 'boolean',
        ];
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();

        if (! $setting) {
            return $default;
        }

        return static::castValue($setting->value, $setting->type);
    }

    public static function set(string $key, mixed $value, string $group = 'general', string $type = 'string', bool $isSensitive = false): void
    {
        static::updateOrCreate(
            ['key' => $key],
            [
                'value'        => is_array($value) ? json_encode($value) : (string) $value,
                'group'        => $group,
                'type'         => $type,
                'is_sensitive' => $isSensitive,
            ]
        );
    }

    private static function castValue(mixed $value, string $type): mixed
    {
        return match ($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $value,
            'json'    => json_decode($value, true),
            default   => $value,
        };
    }
}
