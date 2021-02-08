<?php

/**
 * @file : SendResponse.php  
 * @author : kaushik
 * @uses : adding headers to response
 * 
 */

namespace App\middleware;

use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class SendsResponse
{

/**
* middleware invokable class
*
* @param  ServerRequest  $request PSR-7 request
* @param  RequestHandler $handler PSR-15 request handler
*
* @return Response
*
*/

    public function __invoke(Request $request,RequestHandler $handler) :Response
    {
        $response = $handler->handle($request);
        
        return $response->withHeader('Content-Type','application/json')
        ->withHeader('Access-Control-Allow-Origin','*')
        ->withHeader('Access-Control-Allow-Methods','GET, POST, OPTIONS, PUT, DELETE')
        ->withHeader('Access-Control-Allow-Headers','Acess-Control-Allow-Headers,Content-Type,Acess-Control-Allow-Methods,Authorization');
        
    }
}