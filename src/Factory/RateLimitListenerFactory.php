<?php

namespace Fet\LaminasRateLimiting\Factory;

use Fet\LaminasRateLimiting\Listener\RateLimitListener;
use Fet\LaminasRateLimiting\Service\RateLimiter;
use Fet\LaminasRateLimiting\Service\RouteChecker;
use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class RateLimitListenerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): RateLimitListener
    {
        $rateLimiter = $container->get(RateLimiter::class);
        $routeChecker = $container->get(RouteChecker::class);

        return new RateLimitListener($rateLimiter, $routeChecker);
    }
}
