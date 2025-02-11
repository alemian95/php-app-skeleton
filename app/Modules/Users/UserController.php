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

    public function create(ServerRequestInterface $request): ResponseInterface
    {

        $validated = (new CreateUserValidation($request))->validateBody();

        if ($validated instanceof \Psr\Http\Message\ResponseInterface) {
            return $validated;
        }

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->setPassword($validated['password']);
        $user->save();
        return new JsonResponse($user, 201);
    }

    public function read(ServerRequestInterface $request, int $id): ResponseInterface
    {
        $user = $this->em->find($id);
        if (! $user) {
            return new EmptyResponse(404);
        }
        return new JsonResponse($user);
    }

    public function updatePassword(ServerRequestInterface $request, int $id): ResponseInterface
    {
        $user = $this->em->find($id);

        if (! $user) {
            return new EmptyResponse(404);
        }

        $user->setPassword($request->getParsedBody()['password']);
        $user->save();
        return new JsonResponse($user);
    }
}
