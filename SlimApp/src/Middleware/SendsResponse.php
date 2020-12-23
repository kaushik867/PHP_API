<?php

namespace App\Middleware;

use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class SendsResponse
{
    public function __invoke(Request $request,RequestHandler $handler) :Response
    {
        $response = $handler->handle($request);
        
        return $response->withHeader('Content-Type','application/json')
        ->withHeader('Access-Control-Allow-Origin','*')
        ->withHeader('Access-Control-Allow-Methods','GET, POST, OPTIONS, PUT, DELETE')
        ->withHeader('Access-Control-Allow-Headers','Acess-Control-Allow-Headers,Content-Type,Acess-Control-Allow-Methods');
        
    }
}