<?php
use Slim\App;
use App\Middleware\HttpExceptionMiddleware;


return function(App $app){
    $settings=$app->getContainer()->get('settings');
    $app->addErrorMiddleware($settings['displayErrorsDetails'],$settings['logErrorsDetails'],$settings['logErrors']);
    $app->addBodyParsingMiddleware();
    $app->addRoutingMiddleware();
    $app->setBasePath("/api");
    $app->add(new HttpExceptionMiddleware());
  
};