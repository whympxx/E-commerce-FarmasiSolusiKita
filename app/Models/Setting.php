<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group', 'description'];

    public const CACHE_KEY = 'app_settings';

    /**
     * Ambil satu nilai pengaturan (di-cache; cache dihapus saat pengaturan disimpan).
     */
    public static function get(string $key, $default = null)
    {
        $settings = Cache::rememberForever(self::CACHE_KEY, function () {
            return static::query()->pluck('value', 'key')->all();
        });

        return array_key_exists($key, $settings) && $settings[$key] !== null && $settings[$key] !== ''
            ? $settings[$key]
            : $default;
    }

    public static function flushCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
