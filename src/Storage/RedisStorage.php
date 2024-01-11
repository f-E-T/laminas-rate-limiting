<?php

namespace Fet\LaminasRateLimiting\Storage;

use Redis;

class RedisStorage implements StorageInterface
{
    protected Redis $redis;

    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    public function get($identifier)
    {
        return $this->redis->get($identifier);
    }

    public function set($identifier, $value, $ttl)
    {
        return $this->redis->setex($identifier, $ttl, $value);
    }

    public function increment($identifier)
    {
        return $this->redis->incr($identifier);
    }
}
