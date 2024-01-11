<?php

namespace Fet\LaminasRateLimiting\Tests\Factory;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Fet\LaminasRateLimiting\Factory\RedisStorageFactory;
use Fet\LaminasRateLimiting\Storage\RedisStorage;
use Mockery as m;
use RedisException;

class RedisStorageFactoryTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
    }

    /** @test */
    public function it_returns_a_redis_storage()
    {
        $container = m::mock(ContainerInterface::class);

        $container->shouldReceive('get')
            ->once()
            ->with('config')
            ->andReturn([]);

        $factory = new RedisStorageFactory();

        try {
            if (!extension_loaded('redis')) {
                $this->markTestSkipped('The Redis extension is not installed.');
            }

            $storage = $factory($container, RedisStorage::class);
        } catch (RedisException $e) {
            $this->markTestSkipped('Redis connection could not be established: ' . $e->getMessage());
        }

        $this->assertInstanceOf(RedisStorage::class, $storage);
    }
}
