<?php

declare(strict_types=1);

namespace AlexManno\Drunk\Controllers;

use AlexManno\Drunk\Core\Annotations\Route;
use AlexManno\Drunk\Core\Annotations\RouteGroup;
use AlexManno\Drunk\Core\Services\Validator;
use AlexManno\Drunk\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class UserController
 *
 * @RouteGroup(prefix="/api")
 */
class UserController
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var Validator */
    private $validator;

    /**
     * UserController constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param Validator              $validator
     */
    public function __construct(EntityManagerInterface $entityManager, Validator $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @Route(route="/users", method="GET")
     *
     * @return JsonResponse
     */
    public function all(): JsonResponse
    {
        $users = $this->entityManager->getRepository(User::class)->findAll();

        return new JsonResponse(array_map(function (User $user): array {
            return $user->toArray();
        }, $users));
    }

    /**
     * @Route(route="/users", method="POST")
     *
     * @param ServerRequestInterface $request
     *
     * @return JsonResponse
     */
    public function create(ServerRequestInterface $request): JsonResponse
    {
        $userData = json_decode((string) $request->getBody(), true);

        $validation = $this->validator->validateEntity(User::class, $userData ?? []);

        if ($validation->fails()) {
            return new JsonResponse(
                $validation->errors()->all(),
                400
            );
        }

        $user = User::fromArray($userData);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse([
            'id' => $user->getId(),
        ], 201);
    }

    /**
     * @Route(route="/users/{id}", method="GET")
     *
     * @param ServerRequestInterface $request
     * @param array                  $args
     *
     * @return JsonResponse
     */
    public function get(ServerRequestInterface $request, array $args): JsonResponse
    {
        /** @var User $user */
        $user = $this->entityManager->find(User::class, (int) $args['id']);

        if (null === $user) {
            return new JsonResponse(['error' => 'User not found.'], 404);
        }

        return new JsonResponse($user->toArray());
    }
}
