<?php
/**
 * @file : DbConnection.php
 * @author : kaushik gupta
 * @uses : estabished FM Server Connction, using FM API for PHP call the properties and methods of FM class
 *         and retrives and sends the data in Fm Server   
 *
 */
namespace App\dbHandler;

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

class DbResponse extends FileMaker{

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
 * Checking error in and sending response as data
 * 
 *
 * @return array $data
 */

    function getEmployee()
    {
        $fm = fm::getEmployeeDetails('Contact details');
        
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
 
 * @param  array $postArr 
 *         array of details
 * 
 * @return array $dataInserted as response
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
 * @param  array $args as record id
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
 * @param array $args 
 * @param array $putArr details in key and value pair 
 * 
 * @return array $dataUpdated as response
 * 
 */

    function updateEmployee($args, $putArr)
    {
        $fm = fm::updateEmployeeDetails('Contact Details',$putArr,$args['id']);
        if($fm instanceof FileMaker_Error)
        {
            return $this->sendError($fm);
        }
        else
        {
            return $this->dataUpdated;
        }
    }
  

}