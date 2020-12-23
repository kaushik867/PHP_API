<?php
use Slim\App;

return function(App $app){
    $settings=$app->getContainer()->get('settings');
    $app->addErrorMiddleware($settings['displayErrorsDetails'],$settings['logErrorsDetails'],$settings['logErrors']);
    $app->addBodyParsingMiddleware();
    $app->addRoutingMiddleware();
    
};