<?php

namespace Src\Core;

use DI\ContainerBuilder;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\ResponseFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function DI\create;

class Application
{

    public string $appPath;
    public string $srcPath;

    public ContainerInterface $container;

    public function __construct(string $appPath)
    {
        $this->appPath = $appPath;
        $this->srcPath = dirname(__DIR__);
        $this->container = $this->buildApplicationComponents();
    }

    public function handle(ServerRequestInterface $request)
    {
        /** @var ResponseFactoryInterface */
        $response = $this->container->get(ResponseFactoryInterface::class);
        $createdResponse = $response->createResponse();

        /** @var EntityManagerInterface */
        $entityManager = $this->container->get(EntityManagerInterface::class);

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

        /** @var Configuration */ 
        $config = ORMSetup::createAttributeMetadataConfiguration(
            paths: [ $this->srcPath . "/Entities" ],
            isDevMode: true,
        );

        /** @var Connection */
        $connection = DriverManager::getConnection([
            'driver' => 'pdo_mysql',
            'user'     => getenv('DB_USER'),
            'password' => getenv('DB_PASSWORD'),
            'dbname'   => getenv('DB_NAME'),
            'host'     => getenv('DB_HOST'),
            'port'     => getenv('DB_PORT'),
        ], $config);

        $builder->addDefinitions([
            ResponseFactoryInterface::class => create(ResponseFactory::class),
            Connection::class => $connection,
            EntityManagerInterface::class => function () use ($connection, $config) {
                return new EntityManager($connection, $config);
            }
        ]);

        return $builder->build();
    }

}
