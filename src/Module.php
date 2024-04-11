<?php

namespace Fet\LaminasRateLimiting;

class Module
{
    /** @return array<mixed> */
    public function getConfig(): array
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
