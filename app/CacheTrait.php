<?php

namespace App;

use Illuminate\Support\Facades\Cache;

trait CacheTrait
{
    function remember_dynamic_keys($keys_key, $cache_key): void
    {
        $trackedKeys = Cache::get($keys_key, []);
        $trackedKeys[] = $cache_key;
        $trackedKeys = array_unique($trackedKeys);
        Cache::forever($keys_key, $trackedKeys);
    }

    function forget_dynamic_keys($keys_key): void
    {
        $keys = Cache::get($keys_key, []);

        foreach ($keys as $key) {
            Cache::forget($key);
        }

        Cache::forget($keys_key);
    }
}
