<?php

use DI\Container;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\UidProcessor;

return function(Container $container){

     $settings = $container->get('settings');
     $loggerSettings = $settings['logger'];
     $log = new Logger($loggerSettings['name']);
     $processor = new UidProcessor();
     $log->pushProcessor($processor);
     $log->pushHandler(new StreamHandler($loggerSettings['path'], $loggerSettings['level']));
     
     
    //  $log->warning('Foo');
    //  $log->error('Bar');

     return $log;

    
};