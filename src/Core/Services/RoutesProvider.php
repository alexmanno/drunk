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
    public function __invoke(\FastRoute\RouteCollector $r)
    {
        $routes = $this->discovery->getRoutes();
        foreach ($routes as $route) {
            $r->addRoute($route['route_method'], $route['route'], [$route['class_name'], $route['class_method']]);
        }
    }
}
