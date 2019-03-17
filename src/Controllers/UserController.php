<?php

declare(strict_types=1);

namespace AlexManno\Drunk\Controllers;

use AlexManno\Drunk\Core\Annotations\Route;
use AlexManno\Drunk\Entity\User;
use Nette\Utils\Json;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class UserController
{
    /**
     * @Route(route="/users", method="GET")
     *
     * @return JsonResponse
     */
    public function all(): JsonResponse
    {
        return new JsonResponse([
            'lol', 'asd',
        ]);
    }

    /**
     * @Route(route="/users", method="POST")
     * @param ServerRequestInterface $request
     *
     * @return JsonResponse
     */
    public function create(ServerRequestInterface $request): JsonResponse
    {
        return new JsonResponse([
            (string)$request->getBody()
        ]);
    }
}
