<?php

namespace Fet\LaminasRateLimiting\Service;

use Laminas\Mvc\Application;

class RouteChecker
{
    private $application;
    private $routes;

    public function __construct(Application $application, array $config)
    {
        $this->application = $application;
        $this->routes = $config['routes'] ?? [];
    }

    public function isCurrentRouteLimited(): bool
    {
        $routeMatch = $this->application->getMvcEvent()->getRouteMatch();

        if (!$routeMatch) {
            return false;
        }

        $currentRouteName = $routeMatch->getMatchedRouteName();

        foreach ($this->routes as $routePattern) {
            $pattern = '/^' . preg_quote($routePattern, '/') . '$/';

            if (stripos($routePattern, '*') !== false) {
                preg_match('/^([a-zA-Z0-9]+)(.*){1}(\*){1}$/', $routePattern, $matches);
                $pattern = '/^' . $matches[1] . '(' . preg_quote($matches[2], '/') . '.*)?$/';
            }

            if (preg_match($pattern, $currentRouteName)) {
                return true;
            }
        }

        return false;
    }
}
