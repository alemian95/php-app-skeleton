<?php

namespace Src\Validation;

abstract class Validator
{
    protected \Psr\Http\Message\ServerRequestInterface $request;

    public function __construct(\Psr\Http\Message\ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface|array<string, mixed>
     */
    public abstract function validateBody(): \Psr\Http\Message\ResponseInterface|array;
}