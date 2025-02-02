<?php

use Laminas\Diactoros\ServerRequestFactory;
use Src\Core\Application;

require_once __DIR__ . "/../vendor/autoload.php";

$request = ServerRequestFactory::fromGlobals();

$application = new Application(dirname(__DIR__) . "/app");

$application->handle($request);
