<?php

namespace Fet\LaminasRateLimiting\Tests\Cache;

use PHPUnit\Framework\TestCase;
use Fet\LaminasRateLimiting\Storage\RedisStorage;
use Mockery as m;
use Redis;

class RedisStorageTest extends TestCase
{
    protected $storage;
    protected $redis;

    protected function setUp(): void
    {
        $this->redis = m::mock(Redis::class);
        $this->storage = new RedisStorage($this->redis);
    }

    protected function tearDown(): void
    {
        m::close();
    }

    /** @test */
    public function it_returns_the_counter_for_an_identifier()
    {
        $this->redis->shouldReceive('get')
            ->with('testIdentifier')
            ->once()
            ->andReturn(1);

        $this->assertEquals(1, $this->storage->get('testIdentifier'));
    }

    /** @test */
    public function it_sets_the_counter_for_an_identifier()
    {
        $this->redis->shouldReceive('setex')
            ->with('testIdentifier', 3600, 1)
            ->once()
            ->andReturn(true);

        $this->assertTrue($this->storage->set('testIdentifier', 1, 3600));
    }

    /** @test */
    public function it_increments_the_counter_for_an_identifier()
    {
        $this->redis->shouldReceive('incr')
            ->with('testIdentifier')
            ->once()
            ->andReturn(2);

        $this->assertEquals(2, $this->storage->increment('testIdentifier'));
    }
}
