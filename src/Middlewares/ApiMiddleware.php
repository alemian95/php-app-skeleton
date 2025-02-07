<?php

namespace Src\Middlewares;

use Laminas\Diactoros\Response\EmptyResponse;

class ApiMiddleware implements \Psr\Http\Server\MiddlewareInterface
{
    public function process(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Server\RequestHandlerInterface $handler): \Psr\Http\Message\ResponseInterface
    {
        $response = $handler->handle($request);

        if ($request->getMethod() === 'OPTIONS') {
            $response = new EmptyResponse();
        }

        return $response->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
            ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Content-Length, Accept-Encoding, X-XSRF-TOKEN, Authorization, Accept, Origin, Cache-Control, X-Requested-With');
    }
}