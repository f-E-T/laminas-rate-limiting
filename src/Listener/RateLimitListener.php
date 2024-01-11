<?php

namespace Fet\LaminasRateLimiting\Listener;

use Fet\LaminasRateLimiting\Service\RateLimiter;
use Fet\LaminasRateLimiting\Service\RouteChecker;
use Laminas\Mvc\MvcEvent;
use Laminas\Http\PhpEnvironment\Response as HttpResponse;
use Laminas\EventManager\AbstractListenerAggregate;
use Laminas\EventManager\EventManagerInterface;

class RateLimitListener extends AbstractListenerAggregate
{
    protected $rateLimiter;
    protected $routeChecker;

    public function __construct(RateLimiter $rateLimiter, RouteChecker $routeChecker)
    {
        $this->rateLimiter = $rateLimiter;
        $this->routeChecker = $routeChecker;
    }

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch'], 100);
    }

    public function onDispatch(MvcEvent $event)
    {
        if ($this->routeChecker->isCurrentRouteLimited() === false) {
            return;
        }

        $clientIdentifier = $event->getRequest()->getServer()->get('REMOTE_ADDR');

        if (!$this->rateLimiter->isAllowed($clientIdentifier)) {
            $response = new HttpResponse();
            $response->setStatusCode(429);
            $response->setContent('Rate limit exceeded');

            return $response;
        }
    }
}
