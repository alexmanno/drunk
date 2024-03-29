<?php

declare(strict_types=1);

namespace AlexManno\Drunk\Core\Services;

use AlexManno\Drunk\Core\Annotations\Route;
use AlexManno\Drunk\Core\Annotations\RouteGroup;
use AlexManno\Drunk\Core\Models\Route as RouteModel;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class RouteDiscovery
{
    /** @var string */
    private $namespace;

    /** @var string */
    private $directory;

    /**
     * @var Reader
     */
    private $annotationReader;

    /**
     * @var array
     */
    private $routes = [];

    /**
     * RouteDiscovery constructor.
     *
     * @param string $namespace
     * @param string $directory
     * @param Reader $annotationReader
     */
    public function __construct(string $namespace, string $directory, Reader $annotationReader)
    {
        $this->namespace = $namespace;
        $this->annotationReader = $annotationReader;
        $this->directory = $directory;
    }

    /**
     * @throws \ReflectionException
     *
     * @return array|RouteModel[]
     */
    public function getRoutes(): array
    {
        if (! $this->routes) {
            $this->discoverRoutes();
        }

        return $this->routes;
    }

    /**
     * @throws \ReflectionException
     */
    private function discoverRoutes(): void
    {
        $finder = new Finder();
        $finder->files()->in($this->directory);

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $class = $this->namespace . '\\' . $file->getBasename('.php');
            $reflectionClass = new \ReflectionClass($class);

            /** @var RouteGroup $routeGroupAnnotation */
            $routeGroupAnnotation = $this->annotationReader->getClassAnnotation($reflectionClass, RouteGroup::class);

            $prefix = null;
            if (null !== $routeGroupAnnotation) {
                $prefix = $routeGroupAnnotation->getPrefix();
            }

            foreach ($reflectionClass->getMethods() as $method) {
                /** @var Route|null $annotation */
                $annotation = $this->annotationReader->getMethodAnnotation($method, Route::class);

                if (null === $annotation) {
                    continue;
                }

                $this->routes[] = new RouteModel(
                    $class,
                    $method->name,
                    $annotation->getRoute($prefix),
                    $annotation->getMethod()
                );
            }
        }
    }
}
