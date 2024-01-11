<?php

namespace Fet\LaminasRateLimiting\Tests\Listener;

use PHPUnit\Framework\TestCase;
use Fet\LaminasRateLimiting\Listener\RateLimitListener;
use Fet\LaminasRateLimiting\Service\RateLimiter;
use Fet\LaminasRateLimiting\Service\RouteChecker;
use Laminas\Mvc\MvcEvent;
use Laminas\Http\PhpEnvironment\Request;
use Laminas\Http\PhpEnvironment\Response;
use Laminas\Stdlib\Parameters;
use Mockery;

class RateLimitListenerTest extends TestCase
{
    protected $rateLimiter;
    protected $routeChecker;
    protected $rateLimitListener;
    protected $event;

    protected function setUp(): void
    {
        $this->rateLimiter = Mockery::mock(RateLimiter::class);
        $this->routeChecker = Mockery::mock(RouteChecker::class);
        $this->routeChecker->shouldReceive('isCurrentRouteLimited')->andReturn(true);

        $this->rateLimitListener = new RateLimitListener($this->rateLimiter, $this->routeChecker);

        $request = new Request();
        $request->setServer(new Parameters(['REMOTE_ADDR' => '127.0.0.1']));

        $this->event = Mockery::mock(MvcEvent::class);
        $this->event->shouldReceive('getRequest')->andReturn($request);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    /** @test */
    public function it_proceeds_the_request_when_allowed()
    {
        $this->rateLimiter->shouldReceive('isAllowed')->with('127.0.0.1')->andReturn(true);

        $response = $this->rateLimitListener->onDispatch($this->event);

        $this->assertNull($response);
    }

    /** @test */
    public function it_blocks_the_request_when_not_allowed()
    {
        $this->rateLimiter->shouldReceive('isAllowed')->with('127.0.0.1')->andReturn(false);

        $response = $this->rateLimitListener->onDispatch($this->event);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(429, $response->getStatusCode());
        $this->assertEquals('Rate limit exceeded', $response->getContent());
    }

    /** @test */
    public function it_proceeds_the_request_when_route_is_not_limited()
    {
        $routeChecker = Mockery::mock(RouteChecker::class);
        $routeChecker->shouldReceive('isCurrentRouteLimited')->andReturn(false);

        $rateLimitListener = new RateLimitListener($this->rateLimiter, $routeChecker);

        $response = $rateLimitListener->onDispatch($this->event);

        $this->assertNull($response);
    }
}
