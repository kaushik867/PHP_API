<?php

/**
 * @file : router.php  
 * @author : kaushik
 * @uses : route to specific controllers
 * 
 */

use Slim\App;

/**
 * @param Slim\APP $app
 * 
 * 
 * on url call specific controller class
 *
*/

return function(App $app){
    $controller = 'App\controller\EmployeeController';
    $app->get('/employees', $controller . ':getEmpDetails');
    $app->get('/employees/{id}', $controller . ':getEmpDetail');
    $app->post('/employees', $controller . ':addEmpDetail');
    $app->delete('/emmployees/{id}', $controller . ':deleteEmpDetail');
    $app->put('/employees/{id}', $controller . ':updateEmpDetail');
    
};