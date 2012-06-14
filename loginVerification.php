<?php
require('config.php');
require('model/account.php');

$uname=$_POST["uName"];
$pass=$_POST["pWord"];

$account = Account::validate_login($uname, $pass);

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
$_SESSION["OK"]="OK";
$_SESSION["ip"]=$ip;
$_SESSION['user_id'] = $account->id;
$_SESSION["accesslevel"] = $account->status;
$_SESSION["username"] = $account->uname;
if ( strlen($account->passwd) < 100 ){ /* 100 is just arbitrary, PASSWORD() hash is less than 100 at least */
  $_SESSION['passwd_warning'] = true;
}

$return = $root . 'index.php';
if ( isset($_SESSION['return']) ){
	$return = $_SESSION['return'];
}
header("Location: $return");

?>