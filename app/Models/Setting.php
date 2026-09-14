<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'group'];

    private static array $memo = [];

    public static function get(string $key, mixed $default = null): mixed
    {
        if (empty(static::$memo)) {
            static::$memo = Cache::remember('all_settings', 3600, function () {
                return static::pluck('value', 'key')->all();
            });
        }

        return static::$memo[$key] ?? $default;
    }

    public static function set(string $key, mixed $value, string $group = 'general'): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value, 'group' => $group]);
        static::$memo = [];
        Cache::forget('all_settings');
        Cache::forget("setting_{$key}");
    }

    public static function clearCache(): void
    {
        static::$memo = [];
        Cache::forget('all_settings');
        $keys = static::pluck('key')->toArray();
        foreach ($keys as $key) {
            Cache::forget("setting_{$key}");
        }
    }
}
