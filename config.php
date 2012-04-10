<?php

/* NOTE! User configuration is now added in `config.local.php`. See
 * `config.default.php` for help. */

require('config.default.php');
if ( file_exists('config.local.php') ){
  require('config.local.php');
}

$version = "0.7.1";

/* store config errors, if count() != 0 the config errorpage is shown */
$config_error = array();
$config_check = isset($config_check) ? $config_check : ($_SERVER['REQUEST_URI'] == "{$root}config_error.php");

/* Check for all required extensions */
foreach ( array('posix', 'mysqli', 'gd') as $ext ){
  if ( !extension_loaded($ext) ){
    $config_error[] = array(
      'message' => "Required PHP extension '$ext' not loaded."
    );
  }
}

$groupname = $usergroup;
$usergroup = posix_getgrnam($groupname);
$groupinfo = posix_getpwuid(posix_geteuid());

/* Ensure $rrdbase is writable and owned by the correct group */
if ( !is_writable($rrdbase) ){
  $config_error[] = array(
	"message" => "Need write permissions to RRDtool storage.",
	"path" => $rrdbase
  );
}

/* extended checks, only testing when a previous error is detected or if manually testing */
if ( $config_check ){
  if ( !$usergroup ){
    $config_error[] = array(
			    'message' => "The specified usergroup does not exist",
			    'group' => $groupname
			    );
  }

  if ( is_writable($rrdbase) && filegroup($rrdbase) != $usergroup['gid'] ){
    $tmp = posix_getgrgid(filegroup($rrdbase));
    $config_error[] = array(
			    'message' => "The group of the RRDtool storage does not correspond to the selected usergroup.",
			    'path' => $rrdbase,
			    'group' => $usergroup['name'],
			    'current group' => $tmp['name']
			    );
  }

  /* Check that the user apache runs as is a member of the selected group */
  if ( !in_array($usergroup['gid'], posix_getgroups()) ){
    $config_error[] = array(
			    'message' => "User does not belong to the specified usergroup.",
			    'user' => $groupinfo['name'],
			    'group' => $groupname
			    );
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
}

if ( count($config_error) > 0 ){
  if ( !$config_check ){
    $_SESSION['config_error'] = $config_error;
    header("Location: {$root}config_error.php");
    exit;
  }
}


?>
