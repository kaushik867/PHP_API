<?php

use Slim\App;

return function(App $app){
    $app->get('/customers', '\App\controller\RouteController:fetch_all_data');
    $app->get('/customers/{id}', '\App\controller\RouteController:fetch_data');
    $app->post('/customers', '\App\controller\RouteController:add_cust');
    $app->delete('/customers/{id}', '\App\controller\RouteController:del_cust');
    $app->put('/customers/{id}', '\App\controller\RouteController:update_cust');
    
};