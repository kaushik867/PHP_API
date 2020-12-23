<?php

namespace App\Middleware;

use PDOException;
use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;

use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpException;

class HttpExceptionMiddleware 
{
    
    
    public function __invoke(ServerRequestInterface $request,RequestHandlerInterface $handler) :Response
    {
        try {
            return $handler->handle($request);
        } catch (HttpException $httpException) {
            
            $statusCode = $httpException->getCode();
            $response = new Response();
            $errorMessage = array(
                "success"=>false,
                'error'=>$httpException->getMessage(),
                'message'=>$httpException->getDescription(),
                'status_code'=>$httpException->getCode()
            );
            $response->getBody()->write(json_encode($errorMessage));

            return $response->withStatus($statusCode);
        }
    }
}