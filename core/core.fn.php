<?php


// RUN CORE 
function invoke($val, $action){
  GLOBAL $actions;

  if(isset($actions[$action])){
    $fn = $actions[$action];
    if(is_callable($fn)){
      if(!empty($val)){
        $fn($val);
      }else{
        $fn();
      }
    }else{
      echo 'Action is not callable.';
    }
  }else{
    echo 'Action not found.';
  }
}



/**
 * 
 * Principal Runner Fn, control, validating and re-routing actions.
 * 
 */

function runner()
{

  GLOBAL $qs, $config;

  if($config['cors'] == 'true'){
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
  }

  $headers = apache_request_headers();
  $req = Request::getRequest();

  if(isset($req['qs'])){
    list($params, $action) = explode('/', $req['qs']);
    $data = (object) ['params' => $params, 'action' => $action];
  }else{
    $payload = file_get_contents("php://input");
    $data    = json_decode($payload);
  }
  
  if($config['jwtenabled'] && !in_array($data->action, ['login','register']) && !$_GET){

    if(isset($headers['Authorization'])){
      if(jwtcheck($headers['Authorization'])){
        invoke($data->params, $data->action);
      }      
    }else{
      exit('error : Missing Authorization Header');
    }

  }else{
    invoke($data->params, $data->action);
  }


}

