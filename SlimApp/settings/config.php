<?php


namespace Database;

use Slim\Psr7\Response;

class Db{

    function db_connection(){
        $conn = mysqli_connect("localhost","root","","user") or die("sql query failed");
        return $conn;
    }

    function query_failed(){
       return array(
            'success'=>false,
            'error'=>'internal server error',
            'message'=>'something went wrong internaly',
            'status_code'=>500
        );
    }
    function data_not_found(){
       return array(
            'success'=>false,
            'error'=>'404 not found',
            'message' => 'No records found', 
            'status_code' => 404
        );
    }

    function data_inserted(){
        return array(
            'success'=>true,
            'message'=>'data inserted',
            'status_code'=>200
        );
    }
    function data_deleted(){
        return array(
            'Success'=>true,
            'messages'=>'data deleted successfully',
            'status_code'=>200
        );
    }

    function argument_error(){
        return array(
            'success'=>false,
            'error' =>'invalid argument',
            'message'=>'missing argument or something went wrong',
            'status_code'=>405
        );
    }

    function data_updated(){
        return array(
            'success'=>true,
            'message'=>'data update successfully',
            'status_code'=>200
        );
    }
}
