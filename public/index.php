<?php

use Laminas\Diactoros\ServerRequestFactory;
use Src\Core\Application;

require_once __DIR__ . "/../vendor/autoload.php";

$request = ServerRequestFactory::fromGlobals();

$di = require __DIR__ . "/../app/Config/di.php";

$application = new Application(dirname(__DIR__) . "/app", $di);

$application->handle($request);
