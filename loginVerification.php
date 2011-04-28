<?php
$uname=$_POST["uName"];
$pass=$_POST["pWord"];

require("config.inc");

$sql_query = sprintf("SELECT * FROM access WHERE uname='%s' and passwd=PASSWORD('%s') LIMIT 1",
		     mysql_real_escape_string($uname), mysql_real_escape_string($pass));
$result = $db->query($sql_query) or die ("Erro query: " . mysql_error() . "<br>\n");

if ( $result->num_rows == 0 ) {
  header("Location: loginDenied.php");
  exit;
}

if (getenv('HTTP_X_FORWARDED_FOR')){ 
  $ip=getenv('HTTP_X_FORWARDED_FOR'); 
} else { 
  $ip=getenv('REMOTE_ADDR');
}

$row = $result->fetch_assoc();

session_start();
$_SESSION["OK"]="OK";
$_SESSION["ip"]=$ip;;
$_SESSION['user_id'] = $row['id'];
$_SESSION["accesslevel"]=$row["status"];
$_SESSION["username"]=$uname;

header("Location: framesMgnt.php");

?>