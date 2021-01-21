<?php

/**
 * @file : ErrorLog.php
 * @author : kaushik 
 * @uses : log error in .log file
 * 
 */

namespace App\logs;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\UidProcessor;

/**
 * creating new record in FM database
 *
 * @package App\log\ErrorLog
 * @property Logger $log 
 * 
 */

class ErrorLog{

    private $log = null;

/**
 * creating new object of ErrorLog class
 *
 * @param  FileMaker $fm object
 * @param  FileMaker_Error_Object $error
 * 
 * 
 */

    public function __construct($error)
    {
        date_default_timezone_set("Asia/kolkata");   //India time (GMT+5:30)

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