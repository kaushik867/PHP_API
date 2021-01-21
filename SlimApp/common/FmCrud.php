<?php

/**
 * @file : FmCrud.php
 * @author : kaushik
 * @uses : Connect to FM server Database, retrive, create, update, delete records 
 */

namespace Db;
use FileMaker;

class FmCrud extends FileMaker{

/**
 * create FileMaker object
 *
 * @param string database name
 * @param string host ip Address
 * @param string username
 * @param string password
 *
 * @return FileMaker Object
 */
    static private function connect(){
        return new FileMaker($_ENV['DATABASE'], $_ENV['HOST'], $_ENV['USER'], $_ENV['PASSWORD']);
    }

/**
 * call FM object and retrive all data from layout
 *
 * @param string $layout
 
 *
 * @return array $data all records in key and value pair
 */

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

        foreach($records as $record)
        {
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

/**
 * call FM object Find specific record by id
 *
 * @param string $layout
 * @param integer $id
 *
 * @return array $data details of specific Employee
 */

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

/**
 * call FM object, create new record and set data
 *
 * @param string $layout
 * @param array $data
 
 *
 * @return boolean true 
 */

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
            return true;
            
        }

/**
 * call FM object and delete specific record
 *
 * @param string $layour
 * @param interger $id
 *
 * @return void
 */

        static function deleteEmployeeDetails($layout, $id)
        {
            $fm = self::connect();
            $record = $fm->getRecordById($layout,$id);
            if(FileMaker::isError($record))
            {
                return $record;
            }

            $delete = $fm->newDeleteCommand($layout , $id);
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
            if(is_array($relatedEmailSet))
            {
                foreach($relatedEmailSet as $email)
                {
                    $email->delete();
                }
            }
            
        }

/**
 * call FM object and update speficif record by id
 *
 * @param string $layout
 * @param array $data
 * @param integer $id
 * 
 * @return void
 */

        static function updateEmployeeDetails($layout, $data, $id)
        {
            $fm = self::connect();
            
            $record = $fm->getRecordById($layout, $id);
            if(FileMaker::isError($record))
            {
                return $record;
            }
            
            $relatedPhoneSet = $record->getRelatedSet('contacts_PHONENUMBERS');
            $phone = (array_key_exists('mobiletype',$data)) ? $relatedPhoneSet[0]->setField('contacts_PHONENUMBERS::Type_xt',$data['mobiletype']):false;
            $phone = (array_key_exists('number',$data)) ? $relatedPhoneSet[0]->setField('contacts_PHONENUMBERS::Number_xn',$data['number']): false;
            ($phone) ? $relatedPhoneSet[0]->commit(): '';
            
            $relatedEmailSet = $record->getRelatedSet('contacts_EMAIL');
            $email = (array_key_exists('emailtype',$data)) ? $relatedEmailSet[0]->setField('contacts_EMAIL::Type_xt',$data['emailtype']): false;
            $email = (array_key_exists('email',$data)) ? $relatedEmailSet[0]->setField('contacts_EMAIL::Email_xt',$data['email']): false;
            ($email) ? $relatedEmailSet[0]->commit(): ''; 
            
            $update = (array_key_exists('title',$data)) ? $record->setField('Title_xt',$data['title']): false;
            $update = (array_key_exists('firstname',$data)) ? $record->setField('FirstName_xt',$data['firstname']): false;
            $update = (array_key_exists('lastname',$data)) ? $record->setField('LastName_xt',$data['lastname']): false;
            $update = (array_key_exists('jobtitle',$data)) ? $record->setField('JobTitle_xt',$data['jobtitle']): false;
            $update = (array_key_exists('company',$data)) ? $record->setField('Company_xt',$data['company']) : false;
            ($update) ? $record->commit(): '';
        }
    
 }