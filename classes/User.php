<?php
//Better to manage/start session from here


class User{

  public function isAdmin(){
     return isset( $_SESSION['user_role'] ) &&  $_SESSION['user_role'] == 'admin' ;
  }

  public function isLoggedIn(){
     return isset( $_SESSION['logged_in'] );
  }

}


 ?>
