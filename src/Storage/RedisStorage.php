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

    public function get(string $identifier): string|bool
    {
        return $this->redis->get($identifier);
    }

    public function set(string $identifier, string $value, int $ttl): bool
    {
        return $this->redis->setex($identifier, $ttl, $value);
    }

    public function increment(string $identifier): int
    {
        return $this->redis->incr($identifier);
    }
}
