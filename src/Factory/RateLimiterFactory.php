<?php

namespace Fet\LaminasRateLimiting\Factory;

use Psr\Container\ContainerInterface;
use Fet\LaminasRateLimiting\Service\RateLimiter;
use Fet\LaminasRateLimiting\Storage\RedisStorage;
use Laminas\ServiceManager\Factory\FactoryInterface;

class RateLimiterFactory implements FactoryInterface
{
    const DEFAULT_STORAGE = RedisStorage::class;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config')['rate_limiting'];
        $storageServiceName = $config['storage']['name'] ?? self::DEFAULT_STORAGE;
        $storage = $container->get($storageServiceName);
        $enabled = $config['enabled'] ?? true;

        return new RateLimiter($storage, $config['max_requests'], $config['window'], $enabled);
    }
}
