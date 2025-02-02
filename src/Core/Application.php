<?php

namespace Src\Core;

use DI\ContainerBuilder;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
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

    public function handle(ServerRequestInterface $request)
    {
        /** @var ResponseFactoryInterface */
        $response = $this->container->get(ResponseFactoryInterface::class);
        $createdResponse = $response->createResponse();

        $response = new HtmlResponse("<pre>" . json_encode($request->getUri()->getScheme()) . "</pre>", $createdResponse->getStatusCode(), $createdResponse->getHeaders());
        // $response = new JsonResponse([ 'foo' => 'bar' ], $createdResponse->getStatusCode(), $createdResponse->getHeaders());

        $this->sendResponse($response);
    }

    private function sendResponse(ResponseInterface $response) {
        http_response_code($response->getStatusCode());
        foreach ($response->getHeaders() as $key => $values) {
            $value = implode(',', $values);
            header("$key: $value");
        }
        echo $response->getBody();
    }


    private function buildApplicationComponents(): ContainerInterface
    {
        $builder = new ContainerBuilder();

        $builder->addDefinitions([
            ResponseFactoryInterface::class => create(ResponseFactory::class)
        ]);

        return $builder->build();
    }

}
