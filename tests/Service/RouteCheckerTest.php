<?php

namespace Fet\LaminasRateLimiting\Tests\Service;

use PHPUnit\Framework\TestCase;
use Fet\LaminasRateLimiting\Service\RouteChecker;
use Laminas\Mvc\Application;
use Laminas\Router\RouteMatch;
use Mockery as m;

class RouteCheckerTest extends TestCase
{
    protected $routeMatch;
    protected $routeChecker;

    protected function setUp(): void
    {
        $config = [
            'routes' => [
                'foo',
                'bar/*',
                'bat.*',
            ],
        ];

        $event = m::mock(Application::class);
        $event->shouldReceive('getMvcEvent')->andReturn($event);
        $this->routeMatch = $routeMatch = m::mock(RouteMatch::class);
        $event->shouldReceive('getRouteMatch')->andReturn($routeMatch);

        $this->routeChecker = new RouteChecker($event, $config);
    }

    protected function tearDown(): void
    {
        m::close();
    }

    /** @test */
    public function it_returns_true_if_route_is_limited()
    {
        $this->routeMatch->shouldReceive('getMatchedRouteName')->andReturn('foo');
        $this->assertTrue($this->routeChecker->isCurrentRouteLimited());
    }

    /** @test */
    public function it_returns_false_if_route_is_not_limited()
    {
        $this->routeMatch->shouldReceive('getMatchedRouteName')->andReturn('nonexistent');
        $this->assertFalse($this->routeChecker->isCurrentRouteLimited());
    }

    /** 
     * @test
     * @dataProvider wildcardProvider 
     */
    public function it_returns_true_if_wildcard_route_is_limited($route)
    {
        $this->routeMatch->shouldReceive('getMatchedRouteName')->andReturn($route);
        $this->assertTrue($this->routeChecker->isCurrentRouteLimited());
    }

    /** @test */
    public function it_returns_true_if_all_routes_are_limited()
    {
        $config = [
            'routes' => [
                '*',
            ],
        ];

        $event = m::mock(Application::class);
        $event->shouldReceive('getMvcEvent')->andReturn($event);
        $routeMatch = m::mock(RouteMatch::class);
        $event->shouldReceive('getRouteMatch')->andReturn($routeMatch);

        $routeChecker = new RouteChecker($event, $config);

        $routeMatch->shouldReceive('getMatchedRouteName')->andReturn('foo');
        $this->assertTrue($routeChecker->isCurrentRouteLimited());
    }

    public static function wildcardProvider()
    {
        return [
            'no route separator' => ['bar'],
            'slash route separator' => ['bar/index'],
            'point route separator' => ['bat.index'],
        ];
    }
}
