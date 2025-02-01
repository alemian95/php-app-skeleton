<?php

namespace Src\Core;

use DI\ContainerBuilder;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\ResponseFactory;
use Laminas\Diactoros\Stream;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function DI\create;

class Application
{

    public ContainerInterface $container;

    public function __construct()
    {
        $this->container = $this->buildApplicationComponents();
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var ResponseFactoryInterface */
        $response = $this->container->get('response');
        $createdResponse = $response->createResponse();

        $response = new HtmlResponse("<pre>" . json_encode($request->getUri()->getScheme()) . "</pre>", $createdResponse->getStatusCode(), $createdResponse->getHeaders());

        return $response;
    }


    private function buildApplicationComponents(): ContainerInterface
    {
        $builder = new ContainerBuilder();

        $builder->addDefinitions([
            'response' => create(ResponseFactory::class)
        ]);

        return $builder->build();
    }

}
