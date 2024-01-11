<?php

namespace Fet\LaminasRateLimiting\Tests\Factory;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Fet\LaminasRateLimiting\Factory\RateLimiterFactory;
use Fet\LaminasRateLimiting\Service\RateLimiter;
use Fet\LaminasRateLimiting\Storage\StorageInterface;
use Fet\LaminasRateLimiting\Storage\RedisStorage;
use Mockery as m;

class RateLimiterFactoryTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
    }

    /** @test */
    public function it_returns_a_rate_limiter()
    {
        $config = [
            'rate_limiting' => [
                'max_requests' => 100,
                'window' => 60,
            ]
        ];

        $container = m::mock(ContainerInterface::class);
        $storageMock = m::mock(StorageInterface::class);

        $container->shouldReceive('get')
            ->with(RedisStorage::class)
            ->andReturn($storageMock);

        $container->shouldReceive('get')
            ->with('config')
            ->andReturn($config);

        $factory = new RateLimiterFactory();
        $rateLimiter = $factory($container, RateLimiter::class);

        $this->assertInstanceOf(RateLimiter::class, $rateLimiter);
    }

    /** @test */
    public function it_can_use_a_custom_storage_implementation_for_the_rate_limiter()
    {
        $config = [
            'rate_limiting' => [
                'max_requests' => 100,
                'window' => 60,
                'storage' => [
                    'name' => 'customStorage',
                ],
            ]
        ];

        $container = m::mock(ContainerInterface::class);
        $customStorage = m::mock(StorageInterface::class);

        $container->shouldReceive('get')
            ->with('config')
            ->andReturn($config);

        $container->shouldReceive('get')
            ->with('customStorage')
            ->andReturn($customStorage);

        $factory = new RateLimiterFactory();
        $rateLimiter = $factory($container, RateLimiter::class);

        $this->assertInstanceOf(RateLimiter::class, $rateLimiter);
    }
}
