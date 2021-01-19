<?php

namespace App\controller;

use Psr\http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\database\DbConnection;

class RouteController extends DbConnection{
    
    public function getEmpDetails(ServerRequestInterface $request, ResponseInterface $response ){
        $connection = new DbConnection();
        $result = $connection->getEmployee($connection);
        $response->getBody()->write(json_encode($result));
        if(array_key_exists('status', $result))
        {
            return $response->withStatus($result['status']);
        }
        return $response;
    }

    public function getEmpDetail(ServerRequestInterface $request, ResponseInterface $response, $args){
        $connection = new DbConnection();
        $result = $connection->getEmployeeById($connection, $args);
        $response->getBody()->write(json_encode($result));
        if(array_key_exists('status', $result))
        {
            return $response->withStatus($result['status']);
        }
        return $response;
    } 

    public function addEmpDetail(ServerRequestInterface $request, ResponseInterface $response, $args){
        $connection = new DbConnection();
        $postArr = $request->getParsedBody();
        $result = $connection->addEmployee($connection, $postArr);
        $response->getBody()->write(json_encode($result));
        return $response->withStatus($result['status']);
    }

    public function deleteEmpDetail(ServerRequestInterface $request, ResponseInterface $response, $args){
        $connection = new DbConnection();
        $result = $connection->delEmployee($connection,$args);
        $response->getBody()->write(json_encode($result));
        return $response->withStatus($result['status']);
    }

    public function updateEmpDetail(ServerRequestInterface $request, ResponseInterface $response, $args){
        $connection = new DbConnection();
        $putArr = $request->getParsedBody();
        $result = $connection->updateEmployee($connection,$args,$putArr);
        $response->getBody()->write(json_encode($result));
        return $response->withStatus($result['status']);
    }
}
