<?php

namespace Src\Core;

use Closure;
use DI\Definition\Helper\AutowireDefinitionHelper;
use DI\Definition\Helper\CreateDefinitionHelper;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\ResponseFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Laminas\HttpHandlerRunner\RequestHandlerRunner;
use Src\Components\Facade;
use Src\Middlewares\TestMiddleware;

class Application
{

    private readonly string $appPath;
    private string $srcPath;

    private \Psr\Container\ContainerInterface $container;
    private \FastRoute\Dispatcher $routeDispatcher;

    /** @var array<class-string, AutowireDefinitionHelper|CreateDefinitionHelper> */
    private array $di;

    /** @var array<Closure> */
    private array $routeCollectors;

    /**
     * 
     * @param string $appPath
     * @param array<class-string, AutowireDefinitionHelper|CreateDefinitionHelper> $di
     * @param array<Closure> $routeCollectors
     */
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

    public function handle(\Psr\Http\Message\ServerRequestInterface $request): void
    {
        // /** @var \Psr\Http\Message\ResponseFactoryInterface */
        // $response = $this->container->get(\Psr\Http\Message\ResponseFactoryInterface::class);
        // $createdResponse = $response->createResponse();

        // example responses
        // $response = new \Laminas\Diactoros\Response\HtmlResponse("<pre>" . json_encode($request->getUri()->getScheme()) . "</pre>", $createdResponse->getStatusCode(), $createdResponse->getHeaders());
        // // $response = new \Laminas\Diactoros\Response\JsonResponse([ 'foo' => 'bar' ], $createdResponse->getStatusCode(), $createdResponse->getHeaders());

        /** @var \Laminas\Stratigility\IterableMiddlewarePipeInterface */
        $pipeline = $this->container->get(\Laminas\Stratigility\IterableMiddlewarePipeInterface::class);

        $pipeline->pipe(new TestMiddleware());
        $pipeline->pipe(\Laminas\Stratigility\middleware(function ($request, $handler) {
            return $this->dispatch($request);
        }));

        $server = new RequestHandlerRunner(
            $pipeline,
            new SapiEmitter(),
            static function () use ($request) {
                return $request;
            },
            static function (\Throwable $e) {
                $response = (new ResponseFactory())->createResponse(500);
                $response->getBody()->write(sprintf(
                    'An error occurred: %s',
                    $e->getMessage
                ));
                return $response;
            }
        );
        $server->run();

        // $response = $this->dispatch($request);

        // $this->sendResponse($response);
    }

    private function dispatch(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        $route = $this->routeDispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());

        /** @var int */
        $found = $route[0];

        switch ($found) {
            
            case \FastRoute\Dispatcher::NOT_FOUND:
                return new EmptyResponse(404);
            
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                return new EmptyResponse(405);

            case \FastRoute\Dispatcher::FOUND:

                /** @var array<string> */
                $handler = $route[1];

                /** @var array<string, string> */
                $args = $route[2];

                [ $controllerClass, $action ] = $handler;
                $instance = $this->container->get($controllerClass);

                /** @var \Psr\Http\Message\ResponseInterface */
                $response = $instance->{$action}($request, ...$args);
                return $response;

        }

        return new EmptyResponse(500);
    }

    private function sendResponse(\Psr\Http\Message\ResponseInterface $response): void
    {
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
            'appPath' => $this->appPath,
            \Laminas\Stratigility\IterableMiddlewarePipeInterface::class => \Di\create(\Laminas\Stratigility\MiddlewarePipe::class),
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
        return \FastRoute\simpleDispatcher(function (\FastRoute\RouteCollector $r): void {

            foreach($this->routeCollectors as $rc) {
                $rc($r);
            }

        });
    }


}
