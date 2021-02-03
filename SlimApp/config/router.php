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

return function(App $app)
{
    
    $controller = 'App\controller\EmployeeController';
    $app->post('/getToken', $controller . ':authenticate');
    $app->get('/api/employees', $controller . ':getEmpDetails');
    $app->get('/api/employees/{id}', $controller . ':getEmpDetail');
    $app->post('/api/employees', $controller . ':addEmpDetail');
    $app->delete('/api/employees/{id}', $controller . ':deleteEmpDetail');
    $app->put('/api/employees/{id}', $controller . ':updateEmpDetail');
    
};