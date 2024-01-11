<?php

namespace Fet\LaminasRateLimiting\Tests\Factory;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Fet\LaminasRateLimiting\Factory\RedisStorageFactory;
use Fet\LaminasRateLimiting\Storage\RedisStorage;
use Mockery as m;

class RedisStorageFactoryTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
    }

    public function testInvoke()
    {
        $container = m::mock(ContainerInterface::class);

        $container->shouldReceive('get')
            ->once()
            ->with('config')
            ->andReturn([]);

        $factory = new RedisStorageFactory();
        $storage = $factory($container, RedisStorage::class);

        $this->assertInstanceOf(RedisStorage::class, $storage);
    }
}
