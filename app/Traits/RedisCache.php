<?php

namespace App\Traits;

use Illuminate\Support\Facades\Redis;

trait RedisCache
{
    protected function prefix()
    {
        return config('database.redis.options.prefix');
    }

    protected function cacheTime(): float|int
    {
        return 60 * 60 * 24;
    }

    protected function cacheGet($key)
    {
        $value = Redis::get($key);
        return $value ? json_decode($value, true) : null;
    }

    protected function cacheSet($key, $value, $group = null)
    {
        Redis::setex($key, $this->cacheTime(), json_encode($value));
        if ($group)
            Redis::sadd($group, $key);
    }

    //Delete single cache key
    protected function cacheDelete($key)
    {
        Redis::del($this->prefix() . $key);
    }

    //Delete all cache group
    protected function cacheClear($group)
    {
        $group = $this->prefix() . $group;
        $keys = Redis::smembers($group);
        foreach ($keys as $key) {
            Redis::del($key);
        }
        Redis::del($group);
    }
}
