<?php

// SimpleCore

include 'config/config.php';
include 'config/easyAuth.php';
include 'core/core.fn.php';
include 'core/helpers.fn.php';

// Autoloading classes
_autoload();

include 'libs/eloquent.php';

// Actions Zone

$actions = [
  'login'   => function($params = []){ echo generate_token($params->nombre); },
  'register'=> function($params = []){
      $user = User::Create([
         'name' =>  $params->nombre,    
         'email' => $params->email,    
         'password' => password_hash("ahmedkhan",PASSWORD_BCRYPT), 
      ]);

  },
  'trucks'  => function($params = []){ trucks(); },
  'factory' => function($params = []){ 

      echo Response($params); 
      
   },
   'hello'  => function(){ 
   
      $data = [
         'data' => [], 
         'file' => 'html/index.html'
      ];

      $mix = factory('', 'mixing');
      echo $mix->getHtml('html/index.html');

   }
];


/**
 * 
 * Runner
 * 
 */

runner();


