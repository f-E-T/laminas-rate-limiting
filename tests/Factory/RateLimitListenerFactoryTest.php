<?php

namespace Fet\LaminasRateLimiting\Tests\Factory;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Fet\LaminasRateLimiting\Factory\RateLimitListenerFactory;
use Fet\LaminasRateLimiting\Listener\RateLimitListener;
use Fet\LaminasRateLimiting\Service\RateLimiter;
use Fet\LaminasRateLimiting\Service\RouteChecker;
use Mockery as m;

class RateLimitListenerFactoryTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
    }

    /** @test */
    public function it_returns_a_rate_limit_listener()
    {
        $container = m::mock(ContainerInterface::class);
        $rateLimiterMock = m::mock(RateLimiter::class);
        $routeCheckerMock = m::mock(RouteChecker::class);

        $container->shouldReceive('get')
            ->once()
            ->with(RateLimiter::class)
            ->andReturn($rateLimiterMock);

        $container->shouldReceive('get')
            ->once()
            ->with(RouteChecker::class)
            ->andReturn($routeCheckerMock);

        $factory = new RateLimitListenerFactory();
        $listener = $factory($container, RateLimitListener::class);

        $this->assertInstanceOf(RateLimitListener::class, $listener);
    }
}
