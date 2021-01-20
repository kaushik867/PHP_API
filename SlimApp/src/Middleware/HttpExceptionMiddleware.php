<?php
/**
 * @file : HttpExceptionMiddleware.php  
 * @author : kaushik
 * @uses : send error as array
 * 
 */

namespace App\middleware;

use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpException;

class HttpExceptionMiddleware 
{

/**
* middleware invokable class
*
* @param  ServerRequest  $request PSR-7 request
* @param  RequestHandler $handler PSR-15 request handler
*
* @return Response
*/

    public function __invoke(ServerRequestInterface $request,RequestHandlerInterface $handler) :Response
    {
        try 
        {
            $response = $handler->handle($request);
            return $response;
        } 
        catch (HttpException $httpException) 
        {    
            $statusCode = $httpException->getCode();
            $response = new Response();
            $errorMessage = array(
                "success"=>false,
                'error'=>$httpException->getMessage(),
                'message'=>$httpException->getDescription(),
                'status_code'=>$httpException->getCode(),
                
            );
            if($request->getMethod()=='OPTIONS')
            {
                return $response;
            }
            $response->getBody()->write(json_encode($errorMessage));
            return $response->withStatus($statusCode);
        }
    }
}