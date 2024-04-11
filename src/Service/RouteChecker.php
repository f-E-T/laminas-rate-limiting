<?php

namespace Fet\LaminasRateLimiting\Service;

use Laminas\Mvc\Application;

class RouteChecker
{
    private Application $application;

    /** @var array<string> $routes */
    private array $routes;

    /** @param array{'routes'?: string} $config */
    public function __construct(Application $application, array $config = [])
    {
        $this->application = $application;
        $this->routes = (array) ($config['routes'] ?? []);
    }

    public function isCurrentRouteLimited(): bool
    {
        $routeMatch = $this->application->getMvcEvent()->getRouteMatch();

        if (!$routeMatch) {
            return false;
        }

        $currentRouteName = $routeMatch->getMatchedRouteName();

        foreach ($this->routes as $routePattern) {
            if ($this->patternMatchesRouteName($routePattern, $currentRouteName)) {
                return true;
            }
        }

        return false;
    }

    protected function patternMatchesRouteName(string $routePattern, string $currentRouteName): bool
    {
        if ($this->isWildcardPattern($routePattern)) {
            return true;
        }

        $pattern = $this->getPattern($routePattern);

        if (preg_match($pattern, $currentRouteName)) {
            return true;
        }

        return false;
    }

    protected function getPattern(string $routePattern): string
    {
        if ($this->isSubWildcardPattern($routePattern)) {
            return $this->generateWildcardPattern($routePattern);
        }

        return $this->generatePattern($routePattern);
    }

    protected function isWildcardPattern(string $routePattern): bool
    {
        return $routePattern === '*';
    }

    protected function isSubWildcardPattern(string $routePattern): bool
    {
        return stripos($routePattern, '*') !== false;
    }

    protected function generatePattern(string $routePattern): string
    {
        return '/^' . preg_quote($routePattern, '/') . '$/';
    }

    protected function generateWildcardPattern(string $routePattern): string
    {
        $matches = $this->extractSubMatches($routePattern);

        return '/^' . $matches[1] . '(' . preg_quote($matches[2], '/') . '.*)?$/';
    }

    /** @return array<string> */
    protected function extractSubMatches(string $routePattern): array
    {
        preg_match('/^([a-zA-Z0-9]+)(.*){1}(\*){1}$/', $routePattern, $matches);

        return $matches;
    }
}
