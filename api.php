<?php

/*
  ___ ______ _____   _                 _   ___  ___     
 / _ \| ___ \_   _| | |               | |  |  \/  |     
/ /_\ \ |_/ / | |   | |     ___   ___ | | _| .  . | ___ 
|  _  |  __/  | |   | |    / _ \ / _ \| |/ / |\/| |/ _ \
| | | | |    _| |_  | |___| (_) | (_) |   <| |  | |  __/
\_| |_|_|    \___/  \_____/\___/ \___/|_|\_\_|  |_/\___|
                                                        
                                                   

*/

 ////// Definitions
  
 include 'config/config.php';

 define('LIBS', 'libs');
 $qs      = $_SERVER['QUERY_STRING'];
 $factory = array();
 $library = array(
  'mixing' => '',
  'orm'    => $link
  );

////// SimpleCore
 include 'config/_bootstrap.php';

////// Security Actions
 $onlypost = array('save','get');

////// Actions Zone

 function hello(){
  GLOBAL $factory;
  echo factory(null, 'mixing')->getHtml('html/index.html');
}

function trucks($json = false){
  if($json == 'json'){
    echo json_encode(factory(null, 'orm')->query("SELECT * FROM trucks"));  
  }else{
    echo factory(null, 'orm')->toTable("SELECT * FROM trucks"); 
  }
}

function save($data = array()){
  GLOBAL $factory;
  echo 'Saving Data  '.$data['ipclient'];
  factory(null, 'orm')->insert(array('tabla' => 'look_track', 'reg' => array('ip'=>$data['ipclient'], 'hostname'=>$data['hostname'])));
}


////// Simple Execute API

runner();


