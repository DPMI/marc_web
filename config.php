<?php

/* NOTE! User configuration is now added in `config.local.php`. See
 * `config.default.php` for help. */

chdir(dirname(__FILE__));

require('config.default.php');
if ( file_exists('config.local.php') ){
  require('config.local.php');
}

if ( substr($root,  0, 1) != '/' ) $root = "/$root"; /* force leading slash */
if ( substr($root, -1, 1) != '/' ) $root = "$root/"; /* force trailing slash */

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
$dbversion = 7;

/* store config errors, if count() != 0 the config errorpage is shown */
$config_error = array();

/* Check for all required extensions */
foreach ( array('posix', 'mysqli', 'gd') as $ext ){
	if ( !extension_loaded($ext) ){
		$config_error[] = array('message' => "Required PHP extension '$ext' not loaded.");
	}
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

/* required for legacy database connections */
$Connect = @mysql_connect($DB_SERVER, $user, $password);
mysql_select_db($DATABASE,$Connect);

if ( isset($skip_config_check) ){
	return;
}

$groupname = $usergroup;
$usergroup = posix_getgrnam($groupname);
$groupinfo = posix_getpwuid(posix_geteuid());
$max_size=1000000;

$sql_update="SELECT * FROM guiconfig WHERE selected=1";
$result=mysql_query($sql_update);
if(!$result) {
	print "MySQL error: " . mysql_error();
	exit;
}

if(mysql_num_rows($result)>0) {
	$row = mysql_fetch_array($result);
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

$result = mysql_query("SELECT 1 FROM `information_schema`.`tables` WHERE `table_schema` = '$DATABASE' AND `table_name`='version' LIMIT 1");
if ( mysql_num_rows($result) == 1 ){

} else {
	$config_error[] = array(
		"message" => "MySQL schema too old, please upgrade using upgrade/v0.7.1.php (and subsequential files in order) in a shell.",
		"error" => "version too old",
		);
}

$result = mysql_query("SELECT `num` FROM `version` LIMIT 1");
$row = mysql_fetch_array($result);
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
