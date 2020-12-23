<?php

namespace App\Middleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class BadRequest
{

    private $msg = array([
        'success' => false, 
        'error' => 'bad request',
        'message'=>'Client sent an invalid request ',
        'status_code' =>400
    ]);
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = $handler->handle($request);
        $response->getBody()->write(json_encode($this->msg));
        
        return $response->withStatus(400);
    }
}