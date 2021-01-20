<?php
/**
 * @file : index.php  
 * @author : kaushik
 * @uses : Dependency injections and configure slim-app
 * 
 */

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

$Dotenv = $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../vendor/');
$Dotenv->load();

$app->run();