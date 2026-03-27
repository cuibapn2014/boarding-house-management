<?php

namespace App\Support;

use Closure;
use Illuminate\Support\Facades\Cache;

class ListingCache
{
    public const KEY_PREFIX = 'listing:';
    public const REGISTRY_KEY = 'listing:registry';

    public static function remember(string $key, int $ttlSeconds, Closure $callback): mixed
    {
        $fullKey = self::KEY_PREFIX . $key;
        self::registerKey($fullKey);

        return Cache::remember($fullKey, now()->addSeconds($ttlSeconds), $callback);
    }

    public static function clearAll(): int
    {
        $keys = Cache::get(self::REGISTRY_KEY, []);
        $count = 0;

        foreach ($keys as $key) {
            if (Cache::forget($key)) {
                $count++;
            }
        }

        Cache::forget(self::REGISTRY_KEY);

        return $count;
    }

    protected static function registerKey(string $key): void
    {
        $keys = Cache::get(self::REGISTRY_KEY, []);
        if (!in_array($key, $keys, true)) {
            $keys[] = $key;
            Cache::forever(self::REGISTRY_KEY, $keys);
        }
    }
}
