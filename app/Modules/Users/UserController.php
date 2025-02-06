<?php

namespace App\Modules\Users;

use Laminas\Diactoros\Response\JsonResponse;

class UserController
{

    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function all(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        return new JsonResponse($this->repository->all());
    }
}
