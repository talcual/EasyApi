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
 GLOBAL $onlypost, $qs, $config;

    if($config['appserver'] == 'ionic'){
      if (isset($_SERVER['HTTP_ORIGIN'])) {
          header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
          header('Access-Control-Allow-Credentials: true');
          header('Access-Control-Max-Age: 86400');    // cache for 1 day
      }

      if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])){
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
        }

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])){
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            exit(0);
        }
      }

     $payload = file_get_contents("php://input");
     $data = json_decode($payload);

     run($data->getting,$data->action);

  }else{
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

}
