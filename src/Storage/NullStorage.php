<?php

namespace Fet\LaminasRateLimiting\Storage;

class NullStorage implements StorageInterface
{
    public function get(string $key): string|bool
    {
        return false;
    }

    public function set(string $key, string $value, int $ttl): bool
    {
        return true;
    }

    public function increment(string $key): int
    {
        return -1;
    }
}
