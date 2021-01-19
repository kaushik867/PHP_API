<?php

use Slim\App;

return function(App $app){
    $app->get('/employees', '\App\controller\RouteController:getEmpDetails');
    $app->get('/employees/{id}', '\App\controller\RouteController:getEmpDetail');
    $app->post('/employees', '\App\controller\RouteController:addEmpDetail');
    $app->delete('/emmployees/{id}', '\App\controller\RouteController:deleteEmpDetail');
    $app->put('/employees/{id}', '\App\controller\RouteController:updateEmpDetail');
    
};