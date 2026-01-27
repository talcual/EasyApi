<?php

/**
 * 
 * Fn for autoloading libs
 * 
 */

function _autoload(){
    GLOBAL $library;

    foreach (glob(LIBS."/*.php") AS $file_name)  {
        include($file_name);
    }

    foreach (glob("models/*.php") AS $file_name) {
        require($file_name);
    }

    factory($library);
}


/**
 * 
 * Fn for JSON Response
 * 
 */

function Response($data = [], $json = true)
{

    if($json){
        header('Content-Type: application/json; charset=utf-8');
        return json_encode($data);
    }else{
        return $data;
    }

}

/**
 * 
 * Fn for generate JWT Token by passing data
 * 
 */

function generate_token($data){
    try {
      return Auth::SignIn($data);
    } catch (Exception $e) {
      return $e;
    }
}

/**
 * 
 * Fn for checking JWT Token Valid
 * 
 */

function jwtcheck($tk){
    try {
        return Auth::Check($tk);
    } catch (\Throwable $th) {
        echo 'Token expired';
    }
    
}
  

/**
 * 
 * Fn for parsing global vars, $_POST and $_GET
 * 
 */

function parseVars($vars){
    foreach ($vars as $key => $value) {
      $vars[$key] = $value;
    }

    return $vars;
}

/**
 * 
 * Fn for factory objects, instances.
 * 
 */

function factory($libs = '', $get = ''){
    GLOBAL $factory;
    if(!empty($get)){
        return $factory[$get];
    }else{
        foreach ($libs as $key => $value) {
        if(!empty($value)){
            $factory[$key] = new $key($value);
        }else{
            $factory[$key] = new $key();
        }      
        }      
    }
}