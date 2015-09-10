<?php

$host = 'localhost';
$user = '';
$pass = '';
$database = '';

$link = mysqli_connect($host,$user,$pass,$database) or die("Error : Ouch!! => " . mysqli_error($link)); 
