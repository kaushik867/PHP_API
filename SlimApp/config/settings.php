<?php 

/**
 * @file : settings.php  
 * @author : kaushik
 * @uses : setting property of container
 * 
 */

use DI\Container;
use Monolog\Logger;

/**
 * @param Slim\container 
 * 
 * 
 * setting property of container 
 *
*/

return function(Container $container){
    $container->set('settings',function(){

        return [
            'displayErrorsDetails'=>true,
            'logErrorsDetails'=>true,
            'logErrors'=>true,
        ];
    });
};