<?php

namespace App\Modules\Auth;

use App\Modules\Users\UserService;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthController
{

    public function check(ServerRequestInterface $request): ResponseInterface
    {
        return new EmptyResponse(204);
    }

    public function login(ServerRequestInterface $request): ResponseInterface
    {
        $validated = (new LoginValidation($request))->validateBody();

        if ($validated instanceof \Psr\Http\Message\ResponseInterface) {
            return $validated;
        }

        $user = UserService::findByEmail($validated['email']);

        return new JsonResponse($user);
    }
}
