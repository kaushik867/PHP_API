
<?php

use Slim\Factory\AppFactory;


require __DIR__ . '/../vendor/autoload.php';
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Acess-Control-Allow-Headers,Content-Type,Acess-Control-Allow-Method ');

$app = AppFactory::create();

$middleware = require __DIR__ . '/../settings/middleware.php';
$middleware($app);

$route = require __DIR__ . '/../settings/routes.php';
$route($app);
$app->addBodyParsingMiddleware();
require __DIR__ . '/../settings/config.php';

$app->run();