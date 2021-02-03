<?php

/**
 * @file : middleware.php  
 * @author : kaushik
 * @uses : configure middleare and adding functions
 * 
 */

use Slim\App;
use App\middleware\HttpExceptionMiddleware;
use App\middleware\SendsResponse;

/**
 * @param Slim\APP $app
 * 
 * 
 * Add middleware to a Route with the Route instance’s add() method
 *
*/

return function(App $app)
{
    $settings=$app->getContainer()->get('settings');
    $app->addErrorMiddleware($settings['displayErrorsDetails'],$settings['logErrorsDetails'],$settings['logErrors']);
    $app->addBodyParsingMiddleware();
    $app->addRoutingMiddleware();
    // $app->setBasePath("/api");
    $app->add(new HttpExceptionMiddleware());
    $app->add(new SendsResponse());
    $app->add(new Tuupola\Middleware\JwtAuthentication([
        "path" =>"/api",
        "attribute"=>"jwt",
        "secret" => $settings['JWT_SECRET'],
        "algorithm" => ["HS256"],
        "error" => function ($response) {
            $data["status"] = "error";
            $data["message"] = "invalid token";
            return $response->getbody()->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        }
    ]));
};