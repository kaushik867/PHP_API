<?php

namespace App\Middleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class NotFound
{
    private $msg = array([
        'success' => false, 
        'error' => 'Internal server error',
        'message'=>'The requested resource does not exist',
        'status_code' =>404
    ]);

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = $handler->handle($request);
        $response->getBody()->write(json_encode($this->msg));
        return $response->withStatus(404);
    }
}