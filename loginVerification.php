<?php
require('config.php');
require('model/account.php');


if ( !$account ){
  header("Location: loginDenied.php");
  exit;
}

if (getenv('HTTP_X_FORWARDED_FOR')){
  $ip=getenv('HTTP_X_FORWARDED_FOR');
} else {
  $ip=getenv('REMOTE_ADDR');
}

session_start();


?>