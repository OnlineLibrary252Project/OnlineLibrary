<?php
function logger($log){
  if(!file_exists('logger/log.txt')){
    file_put_contents('logger/log.txt','');
  }
  $ip = $_SERVER['REMOTE_ADDR']; //Client IP
  $time = date("Y-m-d  H:i:s", strtotime("+1 hours") );

  $contents = file_get_contents('logger/log.txt');
  $contents .= "$ip\t$time\t$log\r";
  file_put_contents('logger/log.txt',$contents);

}
 ?>
