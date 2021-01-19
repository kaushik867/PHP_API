
<?php 

use DI\Container;
use Monolog\Logger;

return function(Container $container){
    $container->set('settings',function(){

        return [
            'displayErrorsDetails'=>true,
            'logErrorsDetails'=>true,
            'logErrors'=>true,
            'logger' => [
                'name' => 'slim-App',
                'path' => __DIR__ . '/../logs/app.log',
                'level' => Logger::DEBUG,
            ],
        ];
    });
};