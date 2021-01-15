<?php

class dbConnection extends FileMaker {

    private $host='192.168.10.62';
    private $user='Admin';
    private $password='mindfire';
    private $database='Contacts';
    private $fm;

    private $data_inserted = array(
        'success' => true,
        'message' => 'data inserted successfully',
        'status_code'=>201
    );

    private $data_updated = array(
        'success'=>true,
        'message'=>'data updated successfully',
        'status_code'=>200
    );

    private $data_not_found = array(
        'sucess' => false,
        'message' => 'record not found',
        'status_code' => 404
    );

    private $error = array(
        'success'=>false,
        'error'=>'internal server error',
        'message'=>'something went wrong internally',
        'status_code'=>500
    );

    function __construct()
    {
       return parent::FileMaker($this->database, $this->host, $this->user, $this->password); 
    }
    
    function fetch_all($fm){

        $find = $fm->newFindCommand('Contact Details');
        $result = $find->execute();
        if(FileMaker::isError($result)){
            return $this->error;
        }
        $records = $result->getRecords();
    
        $data = array(array());
    
        $i=0;
           foreach($records as $rec){
                $data[$i]['id'] = $rec->getRecordId();
                $data[$i]['title'] = $rec->getField('Title_xt');
                $data[$i]['firstName'] = $rec->getField('FirstName_xt');
                $data[$i]['lastName'] = $rec->getField('LastName_xt');
                $data[$i]['job'] = $rec->getField('JobTitle_xt');
                $data[$i]['Company'] = $rec->getField('Company_xt');
    
                $relatedPhoneRecords = $rec->getRelatedSet('contacts_PHONENUMBERS');
               if(is_array($relatedPhoneRecords)){
                foreach($relatedPhoneRecords as $phoneDetails)
                {
                    $data[$i]['phone'][$phoneDetails->getField('contacts_PHONENUMBERS::Type_xt')] = $phoneDetails->getField('contacts_PHONENUMBERS::Number_xn');
                }
               }
                
                $relatedEmailRecords = $rec->getRelatedSet('contacts_EMAIL');
                if(is_array($relatedEmailRecords)){
                    foreach($relatedEmailRecords as $emialDetails)
                    {
                        $data[$i]['Email'][$emialDetails->getField('contacts_EMAIL::Type_xt')] = $emialDetails->getField('contacts_EMAIL::Email_xt');
                    }
                }
                $i++;
       }
    
       return $data;
    }

    function fetch( $fm ,$args ){
        $rec = $fm->getRecordById('Contact Details',$args['id']);
        if(FileMaker::isError($rec)){
            if($rec->getCode() == 101){
                return $this->data_not_found;
            }
        }
        $data = array();
        $data['id'] = $rec->getRecordId();
        $data['title'] = $rec->getField('Title_xt');
        $data['firstName'] = $rec->getField('FirstName_xt');
        $data['lastName'] = $rec->getField('LastName_xt');
        $data['job'] = $rec->getField('JobTitle_xt');
        $data['Company'] = $rec->getField('Company_xt');

        $relatedPhoneRecords = $rec->getRelatedSet('contacts_PHONENUMBERS');
       if(is_array($relatedPhoneRecords)){
        foreach($relatedPhoneRecords as $phoneDetails)
        {
            $data['phone'][$phoneDetails->getField('contacts_PHONENUMBERS::Type_xt')] = $phoneDetails->getField('contacts_PHONENUMBERS::Number_xn');
        }
       }
        
        $relatedEmailRecords = $rec->getRelatedSet('contacts_EMAIL');
        if(is_array($relatedEmailRecords)){
            foreach($relatedEmailRecords as $emialDetails)
            {
                $data['Email'][$emialDetails->getField('contacts_EMAIL::Type_xt')] = $emialDetails->getField('contacts_EMAIL::Email_xt');
            }
        }
        return $data;
        
    }

    function add_data($fm , $postArr){
        
        if(count($postArr) == 9){
            $rec = $fm->newAddCommand('Contact Details',array(
                'Title_xt' =>$postArr['title'],
                'FirstName_xt' => $postArr['firstname'],
                'LastName_xt' => $postArr['lastname'],
                'Company_xt' => $postArr['company'],
                'JobTitle_xt' => $postArr['jobtitle']
            ));
            
            $result = $rec->execute(); 
            if(FileMaker::isError($result)){
                return $this->error;
            }
            $currentRecord = $result->getFirstRecord();
            $addPhone = $currentRecord->newRelatedRecord('contacts_PHONENUMBERS');
            $addPhone->setField('contacts_PHONENUMBERS::Type_xt',$postArr['mobiletype']);
            $addPhone->setField('contacts_PHONENUMBERS::Number_xn',$postArr['number']);
            $addPhone->commit();
            
            $addEmail = $currentRecord->newRelatedRecord('contacts_EMAIL');
            $addEmail->setField('contacts_EMAIL::Type_xt',$postArr['emailtype']);
            $addEmail->setField('contacts_EMAIL::Email_xt', $postArr['email']);
            $addEmail->commit();
            return $this->data_inserted;
        }
    }

    function del_data($fm, $args){
        $rec = $fm->getRecordById('Contact Details',$args['id']);
        if(FileMaker::isError($rec)){
            if($rec->getCode() == 101){
                return $this->data_not_found;
            }
        }
        $relatedPhoneSet = $rec->getRelatedSet('contacts_PHONENUMBERS');
        foreach($relatedPhoneSet as $phone){
            $phone->delete();
        }
        $relatedEmailSet = $rec->getRelatedSet('contacts_EMAIL');
        foreach($relatedEmailSet as $email){
            $email->delete();
        }
        $datete = $fm->newDeleteCommand('Contact Details' , $args['id']);
        $result = $datete->execute();
        if(FileMaker::isError($result)){
            return $this->error;
        }
        return array('status_code' => 204);
        
    }

    function update_data($fm, $args, $putArr){
        if(count($putArr)==9)
        {
            $rec = $fm->getRecordById('Contact Details', $args['id']);
            if(FileMaker::isError($rec)){
                if($rec->getCode() == 101){
                    return $this->data_not_found;
                }
            }
            $relatedPhoneSet = $rec->getRelatedSet('contacts_PHONENUMBERS');
            foreach($relatedPhoneSet as $phone){
                $phone->setField('contacts_PHONENUMBERS::Type_xt',$putArr['mobiletype']);
                $phone->setField('contacts_PHONENUMBERS::Number_xn',$putArr['number']);
                $phone->commit();
            }
            $relatedEmailSet = $rec->getRelatedSet('contacts_EMAIL');
            foreach($relatedEmailSet as $email){
                $email->setField('contacts_EMAIL::Type_xt',$putArr['emailtype']);
                $email->setField('contacts_EMAIL::Email_xt',$putArr['email']);
                $email->commit(); 
            }
            $rec->setField('Title_xt',$putArr['title']);
            $rec->setField('FirstName_xt',$putArr['firstname']);
            $rec->setField('LastName_xt',$putArr['lastname']);
            $rec->setField('JobTitle_xt',$putArr['jobtitle']);
            $rec->setField('Company_xt',$putArr['company']);
            $result=$rec->commit();
            if(FileMaker::isError($result)){
                return $this->error;
            }
            return $this->data_updated;
            
        }
    }
  

}