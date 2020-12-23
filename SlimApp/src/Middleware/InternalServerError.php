<?php


namespace App\Middleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class InternalServerError
{
    private $msg = array([
        'success' => false, 
        'error' => 'Internal server error',
        'message'=>'A generic error occurred on the server',
        'status_code' =>500
    ]);
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = $handler->handle($request);
        $response->getBody()->write(json_encode($this->msg));
        return $response->withStatus(500);
    }
}