<?php

namespace Src\Middlewares;

class TestMiddleware implements \Psr\Http\Server\MiddlewareInterface
{
    public function process(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Server\RequestHandlerInterface $handler): \Psr\Http\Message\ResponseInterface
    {
        $response = $handler->handle($request);
        return $response->withHeader('X-Test-Middleware', 'This is a test header');
    }
}