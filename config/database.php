<?php
require_once 'app.php';
$connection=[
  'host'=> 'localhost' ,
  'user'=>'root',
  'password'=>'',
  'database'=>'onilne_library'
];
$mysqli= new mysqli($connection['host'],$connection['user'],$connection['password'],$connection['database']);

if($mysqli->connect_error){
  die("Error connecting to the Database " . $mysqli->connect_error );
}
