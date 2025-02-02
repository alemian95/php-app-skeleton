<?php

namespace Src\Core;

class Application
{

    public string $appPath;
    public string $srcPath;

    public \Psr\Container\ContainerInterface $container;

    public function __construct(string $appPath)
    {
        $this->appPath = $appPath;
        $this->srcPath = dirname(__DIR__);
        $this->container = $this->buildApplicationComponents();
    }

    public function handle(\Psr\Http\Message\ServerRequestInterface $request)
    {
        /** @var \Psr\Http\Message\ResponseFactoryInterface */
        $response = $this->container->get(\Psr\Http\Message\ResponseFactoryInterface::class);
        $createdResponse = $response->createResponse();

        /** @var \Doctrine\ORM\EntityManagerInterface */
        $entityManager = $this->container->get(\Doctrine\ORM\EntityManagerInterface::class);

        $response = new \Laminas\Diactoros\Response\HtmlResponse("<pre>" . json_encode($request->getUri()->getScheme()) . "</pre>", $createdResponse->getStatusCode(), $createdResponse->getHeaders());
        // $response = new \Laminas\Diactoros\Response\JsonResponse([ 'foo' => 'bar' ], $createdResponse->getStatusCode(), $createdResponse->getHeaders());

        $this->sendResponse($response);
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

        /** @var \Doctrine\DBAL\Configuration */ 
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
            \Doctrine\ORM\EntityManagerInterface::class => new \Doctrine\ORM\EntityManager($connection, $config)
        ]);

        return $builder->build();
    }

}
