<?php
require_once 'config/app.php';
session_start();
if(isset($_SESSION['logged_in'])){
  $_SESSION=[];
  $_SESSION['success_message']='Logged out successfuly :)';
  header('location: index.php');
  die();
}

 ?>
