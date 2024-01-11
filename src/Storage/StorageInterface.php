<?php

namespace Fet\LaminasRateLimiting\Storage;

interface StorageInterface
{
    public function get($key);

    public function set($key, $value, $ttl);

    public function increment($key);
}
