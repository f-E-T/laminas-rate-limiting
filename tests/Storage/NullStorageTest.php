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

        $this->assertNull($storage->set('foo', 1, 1));
        $this->assertNull($storage->increment('foo'));
        $this->assertEquals(-1, $storage->get('foo'));
    }
}
