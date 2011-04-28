<?php
session_start();

$u_id = -1;
$u_username = null;
$u_access = -1;

if ( isset($_SESSION['OK']) ){
  $u_id = $_SESSION['user_id'];
  $u_username = $_SESSION['username'];
  $u_access = $_SESSION['accesslevel'];
}

?>