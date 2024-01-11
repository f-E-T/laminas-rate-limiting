<?php

namespace Fet\LaminasRateLimiting\Service;

use Fet\LaminasRateLimiting\Storage\StorageInterface;

class RateLimiter
{
    protected $storage;
    protected int $maxRequests;
    protected int $window;

    public function __construct(StorageInterface $storage, int $maxRequests, int $window)
    {
        $this->storage = $storage;
        $this->maxRequests = $maxRequests;
        $this->window = $window;
    }

    public function isAllowed(string $clientIdentifier): bool
    {
        $key = 'ratelimit:' . $clientIdentifier;
        $current = $this->storage->get($key);

        if ($current !== false) {
            if ($current >= $this->maxRequests) {
                return false;
            }

            $this->storage->increment($key);
        } else {
            $this->storage->set($key, 1, $this->window);
        }

        return true;
    }
}
