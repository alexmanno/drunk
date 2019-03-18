<?php

declare(strict_types=1);

namespace AlexManno\Drunk\Controllers;

use AlexManno\Drunk\Core\Annotations\Route;
use AlexManno\Drunk\Core\Services\Hasher;
use AlexManno\Drunk\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rakit\Validation\Validator;
use Zend\Diactoros\Response\JsonResponse;

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

        return new JsonResponse(array_map(function(User $user): array {
            return [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'firstName' => $user->getFirstMame(),
                'lastName' => $user->getLastName(),
                'email' => $user->getEmail()
            ];
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

        $validation = $this->validator->validate($userData, [
            'username' => 'required|min:4',
            'firstName' => 'required|min:2',
            'lastName' => 'required|min:2',
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

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
}
