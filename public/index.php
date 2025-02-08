<?php

declare(strict_types=1);

use Laminas\Diactoros\ServerRequestFactory;
use Src\Core\Application;

require_once __DIR__ . "/../vendor/autoload.php";

$request = ServerRequestFactory::fromGlobals();

$di = require __DIR__ . "/../app/Config/di.php";
$rc = require __DIR__ . "/../app/Config/router.php";

$application = new Application(dirname(__DIR__) . "/app", $di, $rc);

$application->handle($request);
