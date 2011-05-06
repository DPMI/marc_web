<?php
//CONFIGURATION FILE// Edit with care

/* store config errors, if count() != 0 the config errorpage is shown */
$config_error = array();

//Database configuration
$DB_SERVER="localhost";
$DATABASE='marc';
$user='marc';
$password='konko';
$path='uploadedfiles/';

$prefix = '/home/ext/temp/NPLDP-prefix';
$rrdbase = "$prefix/var/lib/marc";

if ( !is_writable($rrdbase) ){
  $config_error[] = array(
	"message" => "Need write permissions to RRDtool storage.",
	"path" => $rrdbase
  );
}

/* required for BasicObject */
$db = @new mysqli($DB_SERVER, $user, $password, $DATABASE);
define('HTML_ACCESS', 1);

if ( mysqli_connect_error() ){
  $config_error[] = array(
	"message" => "Unable to connect to MySQL database.",
	"error" => mysqli_connect_error(),
  );
}

$max_size=1000000;
$Connect = @mysql_connect($DB_SERVER, $user, $password);

if ( $Connect ){
  mysql_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");

  $sql_update="SELECT * FROM guiconfig WHERE selected=1";
  $result=mysql_query($sql_update);
  if(!$result) {
    print "MySQL error: " . mysql_error();
    exit;
  }
  if(mysql_num_rows($result)>0) {
    $row = mysql_fetch_array($result);
  } else { // PRoblems. Use some default
    
  }
  $pageStyle=$row["pageStyle"];
  $pageStyleBad=$row["pageStyleBad"];
  $projectName=$row["projectName"];
  $selectedID=$row["id"];
}

if ( count($config_error) > 0 ){
  if ( $_SERVER['REQUEST_URI'] != '/config_error.php' ){
    $_SESSION['config_error'] = $config_error;
    header('Location: config_error.php');
    exit;
  }
}

?>
