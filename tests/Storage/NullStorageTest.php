<?php

namespace Fet\LaminasRateLimiting\Tests\Cache;

use Fet\LaminasRateLimiting\Storage\NullStorage;
use PHPUnit\Framework\TestCase;

class NullStorageTest extends TestCase
{
    /** @test */
    public function it_returns_default_values()
    {
        $storage = new NullStorage();

        $this->assertTrue($storage->set('foo', '1', 1));
        $this->assertEquals(-1, $storage->increment('foo'));
        $this->assertFalse($storage->get('foo'));
    }
}
