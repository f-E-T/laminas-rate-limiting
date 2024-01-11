<?php

namespace Fet\LaminasRateLimiting\Storage;

class NullStorage implements StorageInterface
{
    public function get($key)
    {
        return -1;
    }

    public function set($key, $value, $ttl)
    {
    }

    public function increment($key)
    {
    }
}
