<?php

declare(strict_types=1);

namespace AlexManno\Drunk\Core;

use AlexManno\Drunk\Core\Services\CommandsDiscovery;
use AlexManno\Drunk\Core\Services\RouteDiscovery;
use AlexManno\Drunk\Core\Services\RoutesProvider;
use DI\ContainerBuilder;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManagerInterface;
use Dotenv\Dotenv;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class Kernel
{
    /** @var ContainerInterface */
    private $container;

    /**
     * @throws \Exception
     */
    public function boot(): void
    {
        $baseDir = dirname(__DIR__, 2);

        $envs = Dotenv::create($baseDir);
        $envs->load();

        $containerBuilder = new ContainerBuilder();

        $containerBuilder->addDefinitions([
            RouteDiscovery::class => function (ContainerInterface $container) use ($baseDir) {
                return new RouteDiscovery(
                    'AlexManno\\Drunk\\Controllers',
                    'Controllers',
                    $baseDir,
                    $this->container->get(AnnotationReader::class)
                );
            },
            CommandsDiscovery::class => function (ContainerInterface $container) use($baseDir) {
                return new CommandsDiscovery(
                    'AlexManno\\Drunk\\Commands',
                    'Commands',
                    $baseDir
                );
            },
            EntityManagerInterface::class => function(ContainerInterface $container) {
                return Doctrine::setUp();
            }
        ]);

        $this->container = $containerBuilder->build();
        $this->container->set('base_dir', $baseDir);
        $this->container->set(Kernel::class, $this);
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $dispatcher = \FastRoute\cachedDispatcher($this->container->get(RoutesProvider::class),[
            'cacheFile' => $this->container->get('base_dir') . '/var/cache/routes.cache',
            'disableCache' => $_SERVER['ENVIRONMENT'] !== 'prod',
        ]);

        $httpMethod = $request->getMethod();
        $uri = $request->getUri();

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri->getPath());
        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                return new JsonResponse(['error' => 'Not found.'], 404);
                break;
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                //$allowedMethods = $routeInfo[1];

                return new JsonResponse(['error' => 'Method not allowed.'], 405);
                break;
            case \FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];

                $controller = $this->container->get($handler[0]);

                return $controller->{$handler[1]}($request, ...$vars);

                break;
        }

        return new JsonResponse(['error' => 'Internal server error'], 500);
    }
}
