<?php

function NotifyUsersByEmail($UsersEmails){
    foreach ($UsersEmails as $email) {
      //TODO: send messages to these emails
       echo $email['email'];
   }
  }
 ?>
