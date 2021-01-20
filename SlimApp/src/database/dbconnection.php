<?php
/**
 * @file : DbConnection.php
 * @author : kaushik gupta
 * @uses : estabished FM Server Connction, using FM API for PHP call the properties and methods of FM class
 *         and retrives and sends the data in Fm Server   
 *
 */
namespace App\database;

use FileMaker;
use Db\FmCrud as fm;
use App\logs\ErrorLog;
use FileMaker_Error;

/**
 * creating an instance of FileMaker, uses the properties and methods form FM API for PHP
 * 
 * @package App\database\DbConnection
 * @property array $dataInserted 
 * @property array $dataUpdated 
 * @property array $dataNotFound 
 * @property array $error 
 * 
 * @method getEmployee      @return $response PSR-7 response
 * @method getEmployeeById  @return $response PSR-7 response
 * @method addEmployee      @return $response PSR-7 response
 * @method delEmployee      @return $response PSR-7 response
 * @method updateEmployee   @return $response PSR-7 response
 * 
 */

class DbConnection extends FileMaker{

    private $dataInserted = array(
        'success' => true,
        'message' => 'data inserted successfully',
        'status'=>201
    );

    private $dataUpdated = array(
        'success'=>true,
        'message'=>'data updated successfully',
        'status'=>200
    );

    private $dataNotFound = array(
        'sucess' => false,
        'message' => 'record not found',
        'status' => 404
    );

    private $error = array(
        'success'=>false,
        'error'=>'internal server error',
        'message'=>'something went wrong internally',
        'status'=>500
    );

/**
 * create DbConnection object constructor
 *
 * @param string database name
 * @param string host ip Address
 * @param string username
 * @param string password
 *
 * @return FileMaker Object
 */

    // function __construct()
    // {
    //    return parent::FileMaker($_ENV['DATABASE'], $_ENV['HOST'], $_ENV['USER'], $_ENV['PASSWORD']); 
    // }

    function sendError($error)
    {
        new ErrorLog($error);
        if($error->getCode() != 101)
        {
            return $this->error;
        }
        else
        {
            return $this->dataNotFound;
        }
        
    }
    
/**
 * finding all records from Contact Details layout
 *
 * @param  FileMaker $fm object
 * 
 *
 * @return array $data
 */

    function getEmployee()
    {
        $fm = fm::getEmployeeDetails('Contact detail');
        
        if($fm instanceof FileMaker_Error)
        {
            return $this->sendError($fm);
        }
        else
        {
            return $fm;
        }
    }

/**
 * finding specific record by id
 *
 * @param  FileMaker $fm object
 * @param  array $args id of the record
 *
 * @return array $data
 */

    function getEmployeeById( $args )
    {
        $fm = fm::getEmployeeDetail('Contact Details', $args['id']);

        if($fm instanceof FileMaker_Error)
        {
            return $this->sendError($fm);
        }
        else
        {
            return $fm;
        }
        
    }

/**
 * creating new record in FM database
 *
 * @param  FileMaker $fm object
 * @param  array $postArr 
 *         array of details
 * 
 * @return array $dataInserted
 * 
 */

    function addEmployee($postArr)
    {   
        $fm = fm::addEmployeeDetails('Contact Details',$postArr);
        if($fm instanceof FileMaker_Error)
        {
            return $this->sendError($fm);
        }
        else 
        {
            return $this->dataInserted;
        }
    }

/**
 * deleting a record in FM database by id
 *
 * @param  FileMaker $fm object
 * @param  array $args 
 * 
 * @return array with status code
 * 
 */

    function delEmployee($args)
    {
        $fm = fm::deleteEmployeeDetails('Contact Details',$args['id']);
        if($fm instanceof FileMaker_Error)
        {
            return $this->sendError($fm);
        }
        else
        {
            return array('status' => 204);
        }
        
    }

/**
 * update record in FM database using id
 *
 * @param  FileMaker $fm object
 * @param  array $args 
 * 
 * @return array $dataUpdated
 * 
 */

    function updateEmployee($fm, $args, $putArr)
    {
        
    }
  

}