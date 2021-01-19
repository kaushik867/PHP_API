<?php
namespace App\database;

use FileMaker;
use App\logs\ErrorLog;

class DbConnection extends FileMaker {

    private $data_inserted = array(
        'success' => true,
        'message' => 'data inserted successfully',
        'status'=>201
    );

    private $data_updated = array(
        'success'=>true,
        'message'=>'data updated successfully',
        'status'=>200
    );

    private $data_not_found = array(
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

    function __construct()
    {
       return parent::FileMaker($_ENV['DATABASE'], $_ENV['HOST'], $_ENV['USER'], $_ENV['PASSWORD']); 
    }
    
    function getEmployee($fm)
    {
        $find = $fm->newFindCommand('Contact Details');
        $result = $find->execute();
        if(FileMaker::isError($result))
        {
            new ErrorLog($result);
            return $this->error;
        }

        $records = $result->getRecords();
        $data = array(array());
        $index=0;

        foreach($records as $record){
            $data[$index]['id'] = $record->getRecordId();
            $data[$index]['title'] = $record->getField('Title_xt');
            $data[$index]['firstname'] = $record->getField('FirstName_xt');
            $data[$index]['lastname'] = $record->getField('LastName_xt');
            $data[$index]['job'] = $record->getField('JobTitle_xt');
            $data[$index]['company'] = $record->getField('Company_xt');
    
            $relatedPhoneRecords = $record->getRelatedSet('contacts_PHONENUMBERS');
            if(is_array($relatedPhoneRecords))
            {
                foreach($relatedPhoneRecords as $phoneDetails)
                {
                    $data[$index]['phone'][$phoneDetails->getField('contacts_PHONENUMBERS::Type_xt')] = $phoneDetails->getField('contacts_PHONENUMBERS::Number_xn');
                }
            }
                
            $relatedEmailRecords = $record->getRelatedSet('contacts_EMAIL');
            if(is_array($relatedEmailRecords)){
                foreach($relatedEmailRecords as $emialDetails)
                {
                    $data[$index]['email'][$emialDetails->getField('contacts_EMAIL::Type_xt')] = $emialDetails->getField('contacts_EMAIL::Email_xt');
                }
            }
            $index++;
       }
    
       return $data;
    }

    function getEmployeeById( $fm ,$args )
    {
        $record = $fm->getRecordById('Contact Details',$args['id']);
        if(FileMaker::isError($record))
        {
            if($record->getCode() == 101)
            {
                new ErrorLog($record);
                return $this->data_not_found;
            }
        }

        $data = array();
        $data['id'] = $record->getRecordId();
        $data['title'] = $record->getField('Title_xt');
        $data['firstname'] = $record->getField('FirstName_xt');
        $data['lastname'] = $record->getField('LastName_xt');
        $data['job'] = $record->getField('JobTitle_xt');
        $data['company'] = $record->getField('Company_xt');

        $relatedPhoneRecords = $record->getRelatedSet('contacts_PHONENUMBERS');
        if(is_array($relatedPhoneRecords))
        {
            foreach($relatedPhoneRecords as $phoneDetails)
            {
                $data['phone'][$phoneDetails->getField('contacts_PHONENUMBERS::Type_xt')] = $phoneDetails->getField('contacts_PHONENUMBERS::Number_xn');
            }
        }
        
        $relatedEmailRecords = $record->getRelatedSet('contacts_EMAIL');
        if(is_array($relatedEmailRecords))
        {
            foreach($relatedEmailRecords as $emialDetails)
            {
                $data['email'][$emialDetails->getField('contacts_EMAIL::Type_xt')] = $emialDetails->getField('contacts_EMAIL::Email_xt');
            }
        }
        return $data;
        
    }

    function addEmployee($fm , $postArr)
    {   
        if(count($postArr) == 9)
        {
            $record = $fm->newAddCommand('Contact Details',array(
                'Title_xt' => $postArr['title'],
                'FirstName_xt' => $postArr['firstname'],
                'LastName_xt' => $postArr['lastname'],
                'Company_xt' => $postArr['company'],
                'JobTitle_xt' => $postArr['jobtitle']
            ));
            
            $result = $record->execute(); 
            if(FileMaker::isError($result))
            {
                return $this->error;
            }
            $currentRecord = $result->getFirstRecord();
            $addPhone = $currentRecord->newRelatedRecord('contacts_PHONENUMBERS');
            $addPhone->setField('contacts_PHONENUMBERS::Type_xt',$postArr['mobiletype']);
            $addPhone->setField('contacts_PHONENUMBERS::Number_xn',$postArr['number']);
            $addPhone->commit();
            $addEmail = $currentRecord->newRelatedRecord('contacts_EMAIL');
            $addEmail->setField('contacts_EMAIL::Type_xt', $postArr['emailtype']);
            $addEmail->setField('contacts_EMAIL::Email_xt', $postArr['email']);
            $addEmail->commit();
            return $this->data_inserted;
        }
    }

    function delEmployee($fm, $args)
    {
        $record = $fm->getRecordById('Contact Details',$args['id']);
        if(FileMaker::isError($record))
        {
            if($record->getCode() == 101)
            {
                return $this->data_not_found;
            }
        }
        $relatedPhoneSet = $record->getRelatedSet('contacts_PHONENUMBERS');
        foreach($relatedPhoneSet as $phone)
        {
            $phone->delete();
        }
        $relatedEmailSet = $record->getRelatedSet('contacts_EMAIL');
        foreach($relatedEmailSet as $email)
        {
            $email->delete();
        }
        $datete = $fm->newDeleteCommand('Contact Details' , $args['id']);
        $result = $datete->execute();
        if(FileMaker::isError($result))
        {
            return $this->error;
        }
        return array('status_code' => 204);
        
    }

    function updateEmployee($fm, $args, $putArr)
    {
        if(count($putArr)==9)
        {
            $record = $fm->getRecordById('Contact Details', $args['id']);
            if(FileMaker::isError($record))
            {
                if($record->getCode() == 101)
                {
                    return $this->data_not_found;
                }
            }
            $relatedPhoneSet = $record->getRelatedSet('contacts_PHONENUMBERS');
            foreach($relatedPhoneSet as $phone)
            {
                $phone->setField('contacts_PHONENUMBERS::Type_xt',$putArr['mobiletype']);
                $phone->setField('contacts_PHONENUMBERS::Number_xn',$putArr['number']);
                $phone->commit();
            }
            $relatedEmailSet = $record->getRelatedSet('contacts_EMAIL');
            foreach($relatedEmailSet as $email)
            {
                $email->setField('contacts_EMAIL::Type_xt',$putArr['emailtype']);
                $email->setField('contacts_EMAIL::Email_xt',$putArr['email']);
                $email->commit(); 
            }
            $record->setField('Title_xt',$putArr['title']);
            $record->setField('FirstName_xt',$putArr['firstname']);
            $record->setField('LastName_xt',$putArr['lastname']);
            $record->setField('JobTitle_xt',$putArr['jobtitle']);
            $record->setField('Company_xt',$putArr['company']);
            $result=$record->commit();
            if(FileMaker::isError($result))
            {
                return $this->error;
            }
            return $this->data_updated;
            
        }
    }
  

}