<?php

namespace App\controller;

use dbConnection;
use Psr\http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Container\ContainerInterface;

class RouteController extends dbConnection{

    private $ci;
    function __construct(ContainerInterface $ci)
    {
        $this->ci = $ci;
    }
    
    public function fetch_all_data(ServerRequestInterface $request, ResponseInterface $response ){
        $con = new dbConnection();
        $result = $con->fetch_all($con);
        $response->getBody()->write(json_encode($result));
        if(array_key_exists('status_code', $result))
        {
            return $response->withStatus($result['status_code']);
        }
        return $response;
    }

    public function fetch_data(ServerRequestInterface $request, ResponseInterface $response, $args){
        $con = new dbConnection();
        $result = $con->fetch($con, $args);
        $response->getBody()->write(json_encode($result));
        if(array_key_exists('status_code', $result))
        {
            return $response->withStatus($result['status_code']);
        }
        return $response;
    } 

    public function add_cust(ServerRequestInterface $request, ResponseInterface $response, $args){
        $con = new dbConnection();
        $postArr = $request->getParsedBody();
        $result = $con->add_data($con, $postArr);
        $response->getBody()->write(json_encode($result));
        return $response->withStatus($result['status_code']);
    }

    public function del_cust(ServerRequestInterface $request, ResponseInterface $response, $args){
        $con = new dbConnection();
        $result = $con->del_data($con,$args);
        $response->getBody()->write(json_encode($result));
        return $response->withStatus($result['status_code']);
    }

    public function update_cust(ServerRequestInterface $request, ResponseInterface $response, $args){
        $con = new dbConnection();
        $putArr = $request->getParsedBody();
        $result = $con->update_data($con,$args,$putArr);
        $response->getBody()->write(json_encode($result));
        return $response->withStatus($result['status_code']);
    }
}
