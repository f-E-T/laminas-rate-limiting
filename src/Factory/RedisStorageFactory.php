<?php

namespace Fet\LaminasRateLimiting\Factory;

use Fet\LaminasRateLimiting\Storage\RedisStorage;
use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Redis;

class RedisStorageFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): RedisStorage
    {
        $config = $container->get('config')['rate_limiting'] ?? [];
        $options = $config['storage']['options'] ?? [];

        $redis = new Redis();
        $redis->connect($options['host'] ?? '127.0.0.1', $options['port'] ?? 6379);

        if (isset($options['auth'])) {
            $redis->auth($options['auth']);
        }

        return new RedisStorage($redis);
    }
}
