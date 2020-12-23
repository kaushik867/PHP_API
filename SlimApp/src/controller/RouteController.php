<?php

namespace App\controller;

use dbConnection;
use PDOException;
use Psr\http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Container\ContainerInterface;

class RouteController extends dbConnection{

    private $ci;
    private $db_error = array(
        'success'=>false,
        'error'=>'internal server error',
        'message'=>'something went wrong internally',
        'status_code'=>500
    );

    function __construct(ContainerInterface $ci)
    {
        $this->ci = $ci;
    }
    
    public function fetch_all_data(ServerRequestInterface $request, ResponseInterface $response ){
        try{
            $con = new dbConnection();
        $result = $con->fetch_all($con);
        $response->getBody()->write(json_encode($result));
        return $response;

        }catch(PDOException $except){
            $response->getBody()->write(json_encode($this->db_error));
            return $response->withStatus(500);
        }
    }

    public function fetch_data(ServerRequestInterface $request, ResponseInterface $response, $args){
        try{
            $con = new dbConnection();
            $result = $con->fetch($con, $args);
            $response->getBody()->write(json_encode($result));
            return $response;
        }catch(PDOException $except){
            $response->getBody()->write(json_encode($this->db_error));
            return $response->withStatus(500);
        }
    } 

    public function add_cust(ServerRequestInterface $request, ResponseInterface $response, $args){
        try{
            $con = new dbConnection();
            $postArr = $request->getParsedBody();
            $result = $con->add_data($con, $postArr);
            $response->getBody()->write(json_encode($result));
            return $response->withStatus($result['status_code']);
        }catch(PDOException $except){
            $response->getBody()->write(json_encode($this->db_error));
            return $response->withStatus(500);
        }
    }

    public function del_cust(ServerRequestInterface $request, ResponseInterface $response, $args){
        try{
            $con = new dbConnection();
            $result = $con->del_data($con,$args);
            $response->getBody()->write(json_encode($result));
            return $response->withStatus(200);
        }catch(PDOException $except){
            $response->getBody()->write(json_encode($this->db_error));
            return $response->withStatus(500);
        }
    }

    public function update_cust(ServerRequestInterface $request, ResponseInterface $response, $args){
       try{
            $con = new dbConnection();
            $putArr = $request->getParsedBody();
            $result = $con->update_data($con,$args,$putArr);
            $response->getBody()->write(json_encode($result));
            return $response->withStatus(200);
       }catch(PDOException $except){
            $response->getBody()->write(json_encode($this->db_error));
            return $response->withStatus(500);
       }
    }
}
