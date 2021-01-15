<?php

use DI\Container;
use Slim\Factory\AppFactory;
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/FileMaker.php';

$container = new Container();
$settings=require __DIR__ . '/../config/settings.php';
$settings($container);

AppFactory::setContainer($container);
$app = AppFactory::create();

$middleware = require __DIR__ . '/../config/middleware.php';
$middleware($app);

$routes = require __DIR__ . '/../config/router.php';
$routes($app); 

 $app->run();