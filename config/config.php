<?php

define('LIBS', 'libs');

// Setup config
$config = [
    'jwtenabled'    => true, 
    'cors'          => false, 
    'autoresponse'  => 'json',
    'enable_db'     => false
];

// Database Configs
$host = 'localhost';
$user = '';
$pass = '';
$database = '';

if($config['enable_db']){
    $db = mysqli_connect($host,$user,$pass,$database) or die("Error : Ouch!! => " . mysqli_error($db)); 
}

// Setup QueryString in a global variable $qs
$qs      = (isset($_SERVER['QUERY_STRING'])) ? $_SERVER['QUERY_STRING'] : '';

// Factory Array
$factory = array();

// Libraries
$library = array(
 'mixing'  => '',
 'Request' => ''
);