<?php

namespace Fet\LaminasRateLimiting\Storage;

interface StorageInterface
{
    public function get(string $key): string|bool;

    public function set(string $key, string $value, int $ttl): bool;

    public function increment(string $key): int;
}
