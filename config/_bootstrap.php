<?php


// RUN CORE 
function run($val, $callback){
  if(function_exists($callback)){
    _autoload();
    if(!empty($val)){
      $callback($val);
    }else{
      $callback();
    }
  }else{
    echo 'This is a D\'oh';
  }
}

// Parsing Request Vars $_POST & $_GET
function parseVars($vars){
  foreach ($vars as $key => $value) {
    $vars[$key] = $value;
  }
  return $vars;
}


// Autoloading & Instances, Factory Pattern
function _autoload(){
  GLOBAL $library;
  foreach (glob(LIBS."/*.php") AS $nombre_fichero)  {
    include($nombre_fichero);
  }
  factory($library);
}


// Instances Factory
function factory($libs, $get = ''){
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

// Runner Router
function runner(){
 GLOBAL $onlypost, $qs;

  if($_POST){
    $vars = parseVars($_POST);
    if (in_array($vars['action'], $onlypost)) {
      run($vars['getting'],$vars['action']);
    }else{
      echo 'This is a D\'oh';
    }
  }

  if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if($qs == ''){
      run('','hello');
    }else{
      if($_GET){
        $vars = parseVars($_GET);
        if (!in_array($vars['action'], $onlypost)) {
          run($vars['getting'],$vars['action']);
        }else{
          echo 'This is a D\'oh';
        }
      }
    }
  }

}