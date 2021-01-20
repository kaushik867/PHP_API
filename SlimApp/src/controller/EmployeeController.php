<?php

/**
 * @file : RouteController.php
 * @author : kaushik gupta
 * @uses : call as a controller, established FM connection by calling DbConnection class constructor,
 *         call the properties and method from the DbConnection class 
 * 
 */

namespace App\controller;

use Psr\http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\database\DbConnection;

/**
 * controller class extend DbConnection call as a controller class
 * 
 * 
 * @package App\controller\RouteController
 * 
 * @method getEmpDetails    @return $response PSR-7 response 
 * @method getEmpDetail     @return $response PSR-7 response 
 * @method addEmpDetail     @return $response PSR-7 response 
 * @method deleteEmpDetail  @return $response PSR-7 response 
 * @method updateEmpDetail  @return $response PSR-7 response 
 *
 * 
*/

class EmployeeController extends DbConnection{

/**
 * creating an instance of FileMaker and get all Details of Employee
 *
 * @param  ServerRequest  $request PSR-7 request
 * @param  RequestHandler $handler PSR-7 response
 *
 * @return Response
 */
    
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

/**
 * creating an instance of FileMaker and get Details of specific Employee using get from url
 * 
 * @param  ServerRequest  $request PSR-7 request
 * @param  RequestHandler $handler PSR-7 response
 * @param  array $args
 *
 * @return Response
 */

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

/**
 * creating an instance of FileMaker and add Details of Employee
 * 
 * @param  ServerRequest  $request PSR-7 request
 * @param  RequestHandler $handler PSR-7 response
 * 
 *
 * @return Response
 */

    public function addEmpDetail(ServerRequestInterface $request, ResponseInterface $response){
        $connection = new DbConnection();
        $postArr = $request->getParsedBody();
        $result = $connection->addEmployee($connection, $postArr);
        $response->getBody()->write(json_encode($result));
        return $response->withStatus($result['status']);
    }

/**
 * creating an instance of FileMaker and add Details of Employee
 * $args conatins Id of a specific record
 * 
 * @param  ServerRequest  $request PSR-7 request
 * @param  RequestHandler $handler PSR-7 response
 * @param  array $args
 *
 * @return Response
 */

    public function deleteEmpDetail(ServerRequestInterface $request, ResponseInterface $response, $args){
        $connection = new DbConnection();
        $result = $connection->delEmployee($connection,$args);
        $response->getBody()->write(json_encode($result));
        return $response->withStatus($result['status']);
    }

/**
 * creating an instance of FileMaker and update Details of Employee
 * $args conatins id of specific record
 * 
 * @param  ServerRequest  $request PSR-7 request
 * @param  RequestHandler $handler PSR-7 response
 * @param  array $args
 *
 * @return Response
 */

    public function updateEmpDetail(ServerRequestInterface $request, ResponseInterface $response, $args){
        $connection = new DbConnection();
        $putArr = $request->getParsedBody();
        $result = $connection->updateEmployee($connection,$args,$putArr);
        $response->getBody()->write(json_encode($result));
        return $response->withStatus($result['status']);
    }
}