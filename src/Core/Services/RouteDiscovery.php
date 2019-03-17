<?php

declare(strict_types=1);

namespace AlexManno\Drunk\Core\Services;

use AlexManno\Drunk\Core\Annotations\Route;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class RouteDiscovery
{
    /**
     * @var string
     */
    private $namespace;

    /**
     * @var string
     */
    private $directory;

    /**
     * @var Reader
     */
    private $annotationReader;

    /**
     * The Kernel root directory
     *
     * @var string
     */
    private $rootDir;

    /**
     * @var array
     */
    private $routes = [];

    /**
     * RouteDiscovery constructor.
     *
     * @param        $namespace
     * @param        $directory
     * @param        $rootDir
     * @param Reader $annotationReader
     */
    public function __construct($namespace, $directory, $rootDir, Reader $annotationReader)
    {
        $this->namespace = $namespace;
        $this->annotationReader = $annotationReader;
        $this->directory = $directory;
        $this->rootDir = $rootDir;
    }

    /**
     * @throws \ReflectionException
     *
     * @return array
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
    private function discoverRoutes()
    {
        $path = $this->rootDir . '/src/' . $this->directory;
        $finder = new Finder();
        $finder->files()->in($path);

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $class = $this->namespace . '\\' . $file->getBasename('.php');
            $reflectionClass = new \ReflectionClass($class);
            foreach ($reflectionClass->getMethods() as $method) {
                /** @var Route $annotation */
                $annotation = $this->annotationReader->getMethodAnnotation($method, Route::class);

                if (! $annotation) {
                    continue;
                }

                /* @var Route $annotation */
                $this->routes[] = [
                    'class_name' => $class,
                    'class_method' => $method->name,
                    'route' => $annotation->getRoute(),
                    'route_method' => $annotation->getMethod(),
                ];
            }
        }
    }
}
