<?php

namespace Src\Validation;

abstract class Validator
{
    protected \Psr\Http\Message\ServerRequestInterface $request;

    public function __construct(\Psr\Http\Message\ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    public abstract function validateBody(): bool|\Psr\Http\Message\ResponseInterface|null;
}