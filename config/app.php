<?php
include_once 'database.php';

$settings = $mysqli->query("select * from settings where id=1")->fetch_assoc();

//print_r($settings);

if(count($settings)){
  $app_name = $settings['app_name'];
  $admin_email = $settings['admin_email'];
}else {
  $app_name = "Onilne Library app";
  $admin_email ="abonoofl9@gmail.com";
}

$config=[
  'app_name'=>   $app_name ,
  'lang'=>'en',
  'dir'=>'ltr',
  'app_url'=>'http://127.0.0.1/OnlineLibrary/',
  'admin_email' =>   $admin_email ,
  'admin_assets' => 'http://127.0.0.1/OnlineLibrary/admin/template/assets/',
  'project_name' => 'OnlineLibrary'
];
