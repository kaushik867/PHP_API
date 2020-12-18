<?php

use Slim\App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


return function(App $app){
    $app->get('/customers',  function (Request $request, Response $response, $args) {
        $db_connection = new Database\Db();
        $sql = "SELECT * FROM users_table";
        
        $result = mysqli_query($db_connection->db_connection(),$sql);
        if(!$result){
            $response->getBody()->write(json_encode(
                $db_connection->query_failed()),JSON_PRETTY_PRINT);

            return $response->withStatus(500)
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Access-Control-Allow-Origin','*');
        }
        elseif($result and mysqli_num_rows($result) > 0 ){
            $output = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $response->getBody()->write(json_encode($output, JSON_PRETTY_PRINT));
            return $response->withStatus(200)
            ->withHeader('Content-Type','application/json')
            ->withHeader('Access-Control-Allow-Origin','*');
        }
        else{
            $response->getBody()->write(json_encode($db_connection->data_not_found(),JSON_PRETTY_PRINT));
            return $response->withStatus(404)
            ->withHeader('Content-Type','application/json')
            ->withHeader('Access-Control-Allow-Origin','*');
        }
        
    });

    $app->get('/customers/{id}',  function (Request $request, Response $response, $args) {
        $db_connection = new Database\Db();
        $sql = "SELECT * FROM users_table WHERE id = {$args['id']}";
        $result = mysqli_query($db_connection->db_connection(),$sql);
        if(!$result){
            $response->getBody()->write(json_encode(
                $db_connection->query_failed()),JSON_PRETTY_PRINT);
            return $response->withStatus(500)
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Access-Control-Allow-Origin','*');
        }
        elseif($result and mysqli_num_rows($result) > 0){
            $output = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $response->getBody()->write(json_encode($output, JSON_PRETTY_PRINT));
            return $response->withStatus(200)
            ->withHeader('Content-Type','application/json')
            ->withHeader('Access-Control-Allow-Origin','*');
        }
        else{
            $response->getBody()->write(json_encode($db_connection->data_not_found(),JSON_PRETTY_PRINT));
            return $response->withStatus(404)
            ->withHeader('Content-Type','application/json')
            ->withHeader('Access-Control-Allow-Origin','*');
        }
        
    });

    $app->post('/add_customer', function(Request $request, Response $response, $args){
        $db_connection = new Database\Db;
        $postArr  = $request->getParsedBody();
        $sql = "INSERT INTO users_table (name, email, phone) VALUES ( '{$postArr['name']}' , '{$postArr['email']}',{$postArr['phone']})";
        $result=mysqli_query($db_connection->db_connection(),$sql);
        if(count($postArr)!=3){
            $response->getBody()->write(json_encode($db_connection->argument_error(),JSON_PRETTY_PRINT));
            return $response->withStatus(405)
            ->withHeader('Content-Type','application/json')
            ->withHeader('Access-Control-Allow-Origin','*');
        }
        elseif(!$result){
            $response->getBody()->write(json_encode($db_connection->query_failed(),JSON_PRETTY_PRINT));
            return $response->withStatus(500)
            ->withHeader('Content-Type','application/json')
            ->withHeader('Access-Control-Allow-Origin','*');
        }
        elseif($result){
            $response->getBody()->write(json_encode($db_connection->data_inserted(),JSON_PRETTY_PRINT));
            return $response->withStatus(200)
            ->withHeader('Content-Type','application/json')
            ->withHeader('Access-Control-Allow-Origin','*');
        }
        
    });

    $app->delete('/delete_customer/{id}', function(Request $request, Response $response, $args){
        $db_connection= new Database\Db;
        
        $sql = "DELETE FROM users_table WHERE id = {$args['id']}";
        $result = mysqli_query($db_connection->db_connection(),$sql);
        if(!$result){
            $response->getBody()->write(json_encode($db_connection->query_failed(),JSON_PRETTY_PRINT));
            return $response->withStatus(500)
            ->withHeader('Content-Type','application/json')
            ->withHeader('Access-Control-Allow-Origin','*');
            
        }
        elseif($result)
        {
            $response->getBody()->write(json_encode($db_connection->data_deleted(),JSON_PRETTY_PRINT));
            return $response->withStatus(200)
            ->withHeader('Content-Type','application/json')
            ->withHeader('Access-Control-Allow-Origin','*');
        }
        else{
            $response->getBody()->write(json_encode($db_connection->argument_error(),JSON_PRETTY_PRINT));
            return $response->withStatus(405)
            ->withHeader('Content-Type','application/json')
            ->withHeader('Access-Control-Allow-Origin','*');
        }
    });

    $app->put('/modify_customer/{id}', function(Request $request, Response $response, $args){
        $db_connection = new Database\Db;
        $putArr = $request->getParsedBody();
        $sql = "UPDATE users_table SET name='{$putArr['name']}', email='{$putArr['email']}', phone='{$putArr['phone']}' WHERE id={$args['id']}";
        $result = mysqli_query($db_connection->db_connection(),$sql);
        
        if(count($putArr)!=3){
            $response->getBody()->write(json_encode($db_connection->argument_error(),JSON_PRETTY_PRINT));
            return $response->withStatus(405)
            ->withHeader('Content-Type','application/json')
            ->withHeader('Access-Control-Allow-Origin','*');
        }
        elseif(!$result){
            $response->getBody()->write(json_encode($db_connection->query_failed(),JSON_PRETTY_PRINT));
            return $response->withStatus(500)
            ->withHeader('Content-Type','application/json')
            ->withHeader('Access-Control-Allow-Origin','*');
        }
        elseif($result){
            $response->getBody()->write(json_encode($db_connection->data_updated(),JSON_PRETTY_PRINT));
            return $response->withStatus(200)
            ->withHeader('Content-Type','application/json')
            ->withHeader('Access-Control-Allow-Origin','*');
        } 
    });

};