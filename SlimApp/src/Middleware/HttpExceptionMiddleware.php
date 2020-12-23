<?php

namespace App\Middleware;

use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpException;

class HttpExceptionMiddleware 
{
    
    
    public function __invoke(ServerRequestInterface $request,RequestHandlerInterface $handler) :Response
    {
        try {
            $response = $handler->handle($request);
            return $response;
        } catch (HttpException $httpException) {
            
            $statusCode = $httpException->getCode();
            $response = new Response();
            $errorMessage = array(
                "success"=>false,
                'error'=>$httpException->getMessage(),
                'message'=>$httpException->getDescription(),
                'status_code'=>$httpException->getCode(),
                
            );
            if($request->getMethod()=='OPTIONS'){
                return $response;
            }
            $response->getBody()->write(json_encode($errorMessage));
            return $response->withStatus($statusCode);
        }
    }
}