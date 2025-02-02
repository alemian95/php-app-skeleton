<?php

require_once __DIR__ . '/vendor/autoload.php';

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\DBAL\DriverManager;

$config = new PhpFile('database/migrations.php'); // Or use one of the Doctrine\Migrations\Configuration\Configuration\* loaders

$paths = [ __DIR__ . '/app/Entities'];
$isDevMode = true;

$ORMConfig = ORMSetup::createAttributeMetadataConfiguration($paths, $isDevMode);
$connection = DriverManager::getConnection([
    'memory' => true,
    'driver' => 'pdo_mysql',
    'user'     => getenv('DB_USER'),
    'password' => getenv('DB_PASSWORD'),
    'dbname'   => getenv('DB_NAME'),
    'host'     => getenv('DB_HOST'),
    'port'     => getenv('DB_PORT'),
]);

$entityManager = new EntityManager($connection, $ORMConfig);

return DependencyFactory::fromEntityManager($config, new ExistingEntityManager($entityManager));