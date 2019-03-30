<?php

declare(strict_types=1);

namespace AlexManno\Drunk\Core\Models;

class Route
{
    /** @var string */
    private $className;

    /** @var string */
    private $classMethod;

    /** @var string */
    private $route;

    /** @var string */
    private $routeMethod;

    /**
     * Route constructor.
     *
     * @param string $className
     * @param string $classMethod
     * @param string $route
     * @param string $routeMethod
     */
    public function __construct(string $className, string $classMethod, string $route, string $routeMethod)
    {
        $this->className = $className;
        $this->classMethod = $classMethod;
        $this->route = $route;
        $this->routeMethod = $routeMethod;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @return string
     */
    public function getClassMethod(): string
    {
        return $this->classMethod;
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @return string
     */
    public function getRouteMethod(): string
    {
        return $this->routeMethod;
    }
}
