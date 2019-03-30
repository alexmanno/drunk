<?php

declare(strict_types=1);

namespace AlexManno\Drunk\Core\Services;

use FastRoute\Dispatcher;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class RequestHandler
{
    /** @var Dispatcher */
    private $dispatcher;

    /** @var ContainerInterface */
    private $container;

    /**
     * RequestHandler constructor.
     *
     * @param Dispatcher $dispatcher
     * @param ContainerInterface $container
     */
    public function __construct(Dispatcher $dispatcher, ContainerInterface $container)
    {
        $this->dispatcher = $dispatcher;
        $this->container = $container;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $httpMethod = $request->getMethod();
        $uri = $request->getUri();

        $routeInfo = $this->dispatcher->dispatch($httpMethod, $uri->getPath());
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

                return $controller->{$handler[1]}($request, $vars);

                break;
        }

        return new JsonResponse(['error' => 'Internal server error'], 500);
    }
}
