<?php

namespace Fet\LaminasRateLimiting\Service;

use Fet\LaminasRateLimiting\Storage\StorageInterface;

class RateLimiter
{
    protected StorageInterface $storage;
    protected int $maxRequests;
    protected int $window;
    protected bool $enabled;

    public function __construct(StorageInterface $storage, int $maxRequests, int $window, bool $enabled = true)
    {
        $this->storage = $storage;
        $this->maxRequests = $maxRequests;
        $this->window = $window;
        $this->enabled = $enabled;
    }

    public function isAllowed(string $clientIdentifier): bool
    {
        if ($this->enabled === false) {
            return true;
        }

        $key = 'ratelimit:' . $clientIdentifier;
        $current = $this->storage->get($key);

        if ($current !== false) {
            if ($current >= $this->maxRequests) {
                return false;
            }

            $this->storage->increment($key);
        } else {
            $this->storage->set($key, '1', $this->window);
        }

        return true;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}
