<?php

use DI\Container;
use Slim\Factory\AppFactory;
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Acess-Control-Allow-Headers,Content-Type,Acess-Control-Allow-Method ');
require __DIR__ . '/../vendor/autoload.php';

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