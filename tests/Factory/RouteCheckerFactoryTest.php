<?php

namespace Fet\LaminasRateLimiting\Tests\Factory;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Fet\LaminasRateLimiting\Factory\RouteCheckerFactory;
use Fet\LaminasRateLimiting\Service\RouteChecker;
use Laminas\Mvc\Application;
use Mockery as m;

class RouteCheckerFactoryTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
    }

    /** @test */
    public function it_returns_a_route_checker()
    {
        $container = m::mock(ContainerInterface::class);
        $application = m::mock(Application::class);
        $config = [
            'rate_limiting' => [
                'max_requests' => 100,
                'window' => 60,
                'routes' => [],
            ],
        ];

        $container->shouldReceive('get')
            ->with(Application::class)
            ->andReturn($application);

        $container->shouldReceive('get')
            ->with('config')
            ->andReturn($config);

        $factory = new RouteCheckerFactory();
        $routeChecker = $factory($container, RouteChecker::class);

        $this->assertInstanceOf(RouteChecker::class, $routeChecker);
    }
}
