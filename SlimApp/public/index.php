<?php

/**
 * @file : index.php  
 * @author : kaushik
 * @uses : redirect to '/../bootstrap/index.php
 * 
 */

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers: Acess-Control-Allow-Headers,Content-Type,Acess-Control-Allow-Method,Authorization,Accept');

require __DIR__ . '/../bootstrap/index.php';


