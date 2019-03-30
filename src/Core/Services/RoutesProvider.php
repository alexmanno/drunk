<?php

declare(strict_types=1);

namespace AlexManno\Drunk\Core\Services;

class RoutesProvider
{
    /** @var RouteDiscovery */
    private $discovery;

    /**
     * RoutesProvider constructor.
     *
     * @param RouteDiscovery $discovery
     */
    public function __construct(RouteDiscovery $discovery)
    {
        $this->discovery = $discovery;
    }

    /**
     * @param \FastRoute\RouteCollector $r
     *
     * @throws \ReflectionException
     */
    public function __invoke(\FastRoute\RouteCollector $r): void
    {
        $routes = $this->discovery->getRoutes();
        foreach ($routes as $route) {
            $r->addRoute(
                $route->getRouteMethod(),
                $route->getRoute(),
                [
                    $route->getClassName(),
                    $route->getClassMethod(),
                ]
            );
        }
    }
}
