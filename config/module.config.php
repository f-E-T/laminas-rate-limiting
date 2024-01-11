<?php

use Fet\LaminasRateLimiting\Factory\RateLimitListenerFactory;
use Fet\LaminasRateLimiting\Factory\RateLimiterFactory;
use Fet\LaminasRateLimiting\Factory\RedisStorageFactory;
use Fet\LaminasRateLimiting\Factory\RouteCheckerFactory;
use Fet\LaminasRateLimiting\Listener\RateLimitListener;
use Fet\LaminasRateLimiting\Service\RateLimiter;
use Fet\LaminasRateLimiting\Service\RouteChecker;
use Fet\LaminasRateLimiting\Storage\RedisStorage;

return [
    'service_manager' => [
        'factories' => [
            RateLimiter::class => RateLimiterFactory::class,
            RateLimitListener::class => RateLimitListenerFactory::class,
            RedisStorage::class => RedisStorageFactory::class,
            RouteChecker::class => RouteCheckerFactory::class,
        ],
    ],
    'listeners' => [
        RateLimitListener::class,
    ],
    'rate_limiting' => [
        'enabled' => true,
        'max_requests' => 100,
        'window' => 60,
        'storage' => [
            'name' => RedisStorage::class,
            'options' => [
                'host' => '127.0.0.1',
                'port' => 6379,
                'auth' => null,
            ],
        ],
        'routes' => [],
    ],
];
