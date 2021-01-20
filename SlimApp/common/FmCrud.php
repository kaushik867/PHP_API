<?php

/**
 * @file : FmCrud.php
 * @author : kaushik
 * @uses : Connect to FM server Database, retrive, create, update, delete records 
 */

namespace Db;
use FileMaker;

class FmCrud extends FileMaker{

    static private function connect(){
        return new FileMaker($_ENV['DATABASE'], $_ENV['HOST'], $_ENV['USER'], $_ENV['PASSWORD']);
    }

    static function getEmployeeDetails($layout){
    $fm = self::connect();
    $find = $fm->newFindCommand($layout);
        $result = $find->execute();
        if(FileMaker::isError($result))
        {
            return $result;
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

    static function getEmployeeDetail($layout, $id)
    {
        $fm = self::connect();
        $record = $fm->getRecordById($layout,$id);
        if(FileMaker::isError($record))
        {
            return $record;
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

    static function addEmployeeDetails($layout, $data)
    {
        $fm = self::connect();
        
            $record = $fm->newAddCommand($layout ,array(
                'Title_xt' => $data['title'],
                'FirstName_xt' => $data['firstname'],
                'LastName_xt' => $data['lastname'],
                'Company_xt' => $data['company'],
                'JobTitle_xt' => $data['jobtitle']
            ));
            
            $result = $record->execute(); 
            if(FileMaker::isError($result))
            {
               return $result;
            }
            $currentRecord = $result->getFirstRecord();
            $addPhone = $currentRecord->newRelatedRecord('contacts_PHONENUMBERS');
            $addPhone->setField('contacts_PHONENUMBERS::Type_xt',$data['mobiletype']);
            $addPhone->setField('contacts_PHONENUMBERS::Number_xn',$data['number']);
            $addPhone->commit();
            $addEmail = $currentRecord->newRelatedRecord('contacts_EMAIL');
            $addEmail->setField('contacts_EMAIL::Type_xt', $data['emailtype']);
            $addEmail->setField('contacts_EMAIL::Email_xt', $data['email']);
            $addEmail->commit();
            
        }

        static function deleteEmployeeDetails($layout, $args)
        {
            $fm = self::connect();
            $record = $fm->getRecordById($layout,$args);
            if(FileMaker::isError($record))
            {
                return $record;
            }

            $delete = $fm->newDeleteCommand($layout , $args);
            $result = $delete->execute();
            if(FileMaker::isError($result))
            {
                return $result;
            }

            $relatedPhoneSet = $record->getRelatedSet('contacts_PHONENUMBERS');
            if(is_array($relatedPhoneSet))
            {
                foreach($relatedPhoneSet as $phone)
                {
                    $phone->delete();
                }
            }

            $relatedEmailSet = $record->getRelatedSet('contacts_EMAIL');
            if(is_array($relatedPhoneSet))
            {
                foreach($relatedEmailSet as $email)
                {
                    $email->delete();
                }
            }
            
        }

        static function updateEmployeeDetails($data, $args)
        {
            $fm = self::connect();
            
            $record = $fm->getRecordById('Contact Details', $args['id']);
            if(FileMaker::isError($record))
            {
                return $record;
            }
            $relatedPhoneSet = $record->getRelatedSet('contacts_PHONENUMBERS');
            foreach($relatedPhoneSet as $phone)
            {
                $phone->setField('contacts_PHONENUMBERS::Type_xt',$data['mobiletype']);
                $phone->setField('contacts_PHONENUMBERS::Number_xn',$data['number']);
                $phone->commit();
            }
            $relatedEmailSet = $record->getRelatedSet('contacts_EMAIL');
            foreach($relatedEmailSet as $email)
            {
                $email->setField('contacts_EMAIL::Type_xt',$data['emailtype']);
                $email->setField('contacts_EMAIL::Email_xt',$data['email']);
                $email->commit(); 
            }
            $record->setField('Title_xt',$data['title']);
            $record->setField('FirstName_xt',$data['firstname']);
            $record->setField('LastName_xt',$data['lastname']);
            $record->setField('JobTitle_xt',$data['jobtitle']);
            $record->setField('Company_xt',$data['company']);
            $result=$record->commit();
        }
    
 }