<?php

namespace Fet\LaminasRateLimiting\Tests\Service;

use PHPUnit\Framework\TestCase;
use Fet\LaminasRateLimiting\Service\RateLimiter;
use Fet\LaminasRateLimiting\Storage\StorageInterface;
use Mockery as m;

class RateLimiterTest extends TestCase
{
    private $storage;
    private $rateLimiter;

    protected function setUp(): void
    {
        $this->storage = m::spy(StorageInterface::class);
        $this->rateLimiter = new RateLimiter($this->storage, 100, 60);
    }

    protected function tearDown(): void
    {
        m::close();
    }

    /** @test */
    public function it_returns_true_when_the_limit_is_not_reached()
    {
        $this->storage
            ->shouldReceive('get')
            ->with('ratelimit:testClient')
            ->andReturn(50);

        $this->assertTrue($this->rateLimiter->isAllowed('testClient'));
    }

    /** @test */
    public function it_returns_true_when_the_rate_limiter_is_disabled()
    {
        $rateLimiter = new RateLimiter($this->storage, 100, 60, false);

        $this->storage
            ->shouldReceive('get')
            ->with('ratelimit:testClient')
            ->andReturn(9999);

        $this->assertTrue($rateLimiter->isAllowed('testClient'));
    }

    /** @test */
    public function it_returns_false_when_the_limit_is_reached()
    {
        $this->storage
            ->shouldReceive('get')
            ->with('ratelimit:testClient')
            ->andReturn(100);

        $this->assertFalse($this->rateLimiter->isAllowed('testClient'));
    }

    /** @test */
    public function test_it_starts_the_counter()
    {
        $this->storage
            ->shouldReceive('get')
            ->with('ratelimit:testClient')
            ->andReturn(false);

        $this->storage
            ->shouldReceive('set')
            ->with('ratelimit:testClient', 1, 60)
            ->once();

        $this->assertTrue($this->rateLimiter->isAllowed('testClient'));
    }

    /** @test */
    public function it_increments_the_counter()
    {
        $this->storage
            ->shouldReceive('get')
            ->with('ratelimit:testClient')
            ->andReturn(50);

        $this->storage
            ->shouldReceive('increment')
            ->with('ratelimit:testClient')
            ->andReturn(51);

        $this->assertTrue($this->rateLimiter->isAllowed('testClient'));
    }
}
