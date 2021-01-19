<?php

namespace App\logs;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\UidProcessor;

class ErrorLog{

    public $log;
    public function __construct($error){
        $this->log = new Logger('Slim-app');
        $processor = new UidProcessor();
        $this->log->pushProcessor($processor);
        $this->log->pushHandler(new StreamHandler( __DIR__ . '/../../logs/app.log', Logger::DEBUG));
        $this->log->debug('Error',[
            'code' => $error->getCode(),
            'message'=> $error->getMessage(),
            'file' => $error->backtrace[7]['file'],
            'line' => $error->backtrace[7]['line']
            ]);
    }
    
};