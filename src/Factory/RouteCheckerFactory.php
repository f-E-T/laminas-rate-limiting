<?php

namespace Fet\LaminasRateLimiting\Factory;

use Fet\LaminasRateLimiting\Service\RouteChecker;
use Laminas\Mvc\Application;
use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class RouteCheckerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $application = $container->get(Application::class);
        $config = $container->get('config')['rate_limiting'];

        return new RouteChecker($application, $config);
    }
}
