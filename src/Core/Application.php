<?php

namespace Src\Core;

use Laminas\Diactoros\Response\EmptyResponse;
use Src\Components\Facade;

class Application
{

    private string $appPath;
    private string $srcPath;

    private \Psr\Container\ContainerInterface $container;
    private \FastRoute\Dispatcher $routeDispatcher;

    private array $di;
    private array $routeCollectors;

    public function __construct(
        string $appPath,
        array $di,
        array $routeCollectors
    )
    {
        $this->appPath = $appPath;
        $this->srcPath = dirname(__DIR__);

        $this->di = $di;
        $this->routeCollectors = $routeCollectors;

        $this->container = $this->buildApplicationComponents();
        $this->routeDispatcher = $this->loadDispatcher();

        Facade::setContainer($this->container);
    }

    public function handle(\Psr\Http\Message\ServerRequestInterface $request)
    {
        // /** @var \Psr\Http\Message\ResponseFactoryInterface */
        // $response = $this->container->get(\Psr\Http\Message\ResponseFactoryInterface::class);
        // $createdResponse = $response->createResponse();

        // example responses
        // $response = new \Laminas\Diactoros\Response\HtmlResponse("<pre>" . json_encode($request->getUri()->getScheme()) . "</pre>", $createdResponse->getStatusCode(), $createdResponse->getHeaders());
        // // $response = new \Laminas\Diactoros\Response\JsonResponse([ 'foo' => 'bar' ], $createdResponse->getStatusCode(), $createdResponse->getHeaders());

        $response = $this->dispatch($request);

        $this->sendResponse($response);
    }

    private function dispatch(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        $route = $this->routeDispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());

        switch ($route[0]) {
            
            case \FastRoute\Dispatcher::NOT_FOUND:
                return new EmptyResponse(404);
            
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                return new EmptyResponse(405);

            case \FastRoute\Dispatcher::FOUND:
                [ $controllerClass, $action ] = $route[1];
                $vars = $route[2];
                $instance = $this->container->get($controllerClass);
                return $instance->{$action}($request, ...$vars);

            default:
                return new EmptyResponse(404);

        }
    }

    private function sendResponse(\Psr\Http\Message\ResponseInterface $response) {
        http_response_code($response->getStatusCode());
        foreach ($response->getHeaders() as $key => $values) {
            $value = implode(',', $values);
            header("$key: $value");
        }
        echo $response->getBody();
    }


    private function buildApplicationComponents(): \Psr\Container\ContainerInterface
    {
        $builder = new \DI\ContainerBuilder();

        $builder->useAutowiring(true);

        /** @var \Doctrine\ORM\Configuration */ 
        $config = \Doctrine\ORM\ORMSetup::createAttributeMetadataConfiguration(
            paths: [ $this->srcPath . "/Entities" ],
            isDevMode: true,
        );

        /** @var \Doctrine\DBAL\Connection */
        $connection = \Doctrine\DBAL\DriverManager::getConnection([
            'driver' => 'pdo_mysql',
            'user'     => getenv('DB_USER'),
            'password' => getenv('DB_PASSWORD'),
            'dbname'   => getenv('DB_NAME'),
            'host'     => getenv('DB_HOST'),
            'port'     => getenv('DB_PORT'),
        ], $config);

        $builder->addDefinitions([
            \Psr\Http\Message\ResponseFactoryInterface::class => \DI\create(\Laminas\Diactoros\ResponseFactory::class),
            \Doctrine\DBAL\Connection::class => $connection,
            \Doctrine\DBAL\Configuration::class => \Doctrine\ORM\ORMSetup::createAttributeMetadataConfiguration(
                paths: [ $this->srcPath . "/Entities" ],
                isDevMode: true,
            ),
            \Doctrine\ORM\EntityManagerInterface::class => function () use ($connection, $config) {
                return new \Doctrine\ORM\EntityManager($connection, $config);
            },
            ...$this->di
        ]);

        return $builder->build();
    }

    private function loadDispatcher(): \FastRoute\Dispatcher
    {
        return \FastRoute\simpleDispatcher(function (\FastRoute\RouteCollector $r) {

            foreach($this->routeCollectors as $rc) {
                $rc($r);
            }

        });
    }


}
