<?php

/* NOTE! User configuration is now added in `config.local.php`. See
 * `config.default.php` for help. */
//echo "config.php \n";

//echo "dir=> " . dirname(__FILE__) . ".\n";

require('config.default.php');
if ( file_exists(dirname(__FILE__). '/config.local.php') ){
//   echo "config.local.php \n";
   require('config.local.php');
} else {
 // echo "Missing config.local.php \n";
}

$index = $root . 'index.php';
$ajax = $root . 'ajax.php';

function expand_path($value){
	global $prefix, $sysconfdir, $localstatedir;
	$path = str_replace(array('{PREFIX}', '{SYSCONFDIR}', '{LOCALSTATEDIR}'),
	                   array($prefix, $sysconfdir, $localstatedir),
	                   $value);
	if ( substr($path, -1) != "/" ) $path .= '/';   /* force trailing slash */
	return $path;
}

/* expand paths */
if ( substr($prefix, -1) != "/" ) $prefix .= '/';   /* force trailing slash */
$sysconfdir = expand_path($sysconfdir);
$localstatedir = expand_path($localstatedir);
$rrdbase = expand_path($rrdbase);
$cachedir = expand_path($cachedir);

$version = "0.7.1";
$dbversion = 5;

/* store config errors, if count() != 0 the config errorpage is shown */
$config_error = array();

/* Check for all required extensions */
foreach ( array('posix', 'mysqli', 'gd') as $ext ){
	if ( !extension_loaded($ext) ){
		$config_error[] = array('message' => "Required PHP extension '$ext' not loaded.");
	}
}

/* required for BasicObject */
$db = new mysqli($DB_SERVER, $user, $password, $DATABASE);
define('HTML_ACCESS', 1);

if ( mysqli_connect_error() ){
   echo "Issues with DB : " . mysqli_connect_error() . POP_EOL;
   echo "DB_SERVER = $DB_SERVER \n";
   echo "user= $user \n";
   echo "password = $password \n";
   echo "DATABASE = $DATABASE \n";
   
	$config_error[] = array(
		"message" => "Unable to connect to MySQL database.",
		"error" => mysqli_connect_error(),
	);
}

/* required for legacy database connections */
$Connect = mysqli_connect($DB_SERVER, $user, $password,$DATABASE);

//echo "config.php ==> db= " . mysqli_get_host_info($db) ."\n";
//echo "config.php ==> connect= " . mysqli_get_host_info($Connect) . " \n";
if ( mysqli_connect_error() ){
        $config_error[] = array(
                "message" => "Unable to connect to MySQL database.",
                "error" => mysqli_connect_error(),
        );
}


/*
  mysqli_select_db($DATABASE,$Connect);
*/

if ( isset($skip_config_check) ){
	return;
}

$groupname = $usergroup;
$usergroup = posix_getgrnam($groupname);
$groupinfo = posix_getpwuid(posix_geteuid());
$max_size=1000000;

$sql_update="SELECT * FROM guiconfig WHERE selected=1";
$result=mysqli_query($Connect, $sql_update);
if(!$result) {
	print "MySQL error: " . mysqli_error();
	exit;
}

if(mysqli_num_rows($result)>0) {
	$row = mysqli_fetch_array($Connect, $result);
	$pageStyle=$row["pageStyle"];
	$pageStyleBad=$row["pageStyleBad"];
	$projectName=$row["projectName"];
	$selectedID=$row["id"];
} else { // PRoblems. Use some default
	$pageStyle="";
	$pageStyleBad="";
	$projectName='MArCd';
	$selectedID=-1;
}

$result = mysqli_query($Connect, "SELECT 1 FROM `information_schema`.`tables` WHERE `table_schema` = '$DATABASE' AND `table_name`='version' LIMIT 1");
if ( mysqli_num_rows($result) == 1 ){

} else {
	$config_error[] = array(
		"message" => "MySQL schema too old, please upgrade using upgrade/v0.7.1.php (and subsequential files in order) in a shell.",
		"error" => "version too old",
		);
}

$result = mysqli_query($Connect,"SELECT `num` FROM `version` LIMIT 1");
$row = mysqli_fetch_array($result);
if ( $row[0] < $dbversion ){
	$config_error[] = array(
		"message" => "MySQL schema too old, please execute all upgrade scripts under upgrade/*.php in a shell.",
		"error" => "version too old",
		"current" => $row[0],
			"required" => $dbversion,
		);
}

if ( count($config_error) > 0 ){
  if ( $_SERVER['REQUEST_URI'] != "{$root}config_error.php" ){
    header("Location: {$root}config_error.php");
    exit;
  }
}


?>
