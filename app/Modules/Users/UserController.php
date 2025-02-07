<?php

namespace App\Modules\Users;

use App\Entities\User;
use Doctrine\ORM\EntityRepository;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserController
{

    private UserRepository $repository;

    /** @var EntityRepository<User> */
    private EntityRepository $em;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
        $this->em = $this->repository->getEntityManager()->getRepository(User::class);
    }

    public function all(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse($this->repository->all());
    }

    public function updatePassword(ServerRequestInterface $request, int $id): ResponseInterface
    {
        $user = $this->em->find($id);

        if (! $user) {
            return new EmptyResponse(404);
        }

        $user->setPassword($request->getParsedBody()['password']);
        return new JsonResponse($user);
    }
}
