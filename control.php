<?php

require("sessionCheck.php");
require("config.inc");
require_once('model/MP.php');

if ( !(isset($_GET['id']) && isset($_GET['action'])) ){
  die("400 Bad Request");
}

$id = $_GET['id'];
$action = strtolower($_GET['action']);
$mp = MP::from_id($id);

if ( !$mp ){
  die("invalid mp");
}

$verify = array('remove');

$need_verify = !in_array($action, $verify);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en-US" xml:lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <link rel="shortcut icon" type="image/x-icon" href="http://www.bth.se/favicon.ico" />
    <title>MArC :: Measurement point control</title>
  </head>
  
  <body>
    <div id="content">
      <div class="alert">
	<p>Are you sure you want to remove measurement point "<?=$mp->name?>" and all its data?<br/>This action is unreversable.</p>
	<p><a href="">Remove</a>&nbsp;&nbsp;&nbsp;<a href="listMPs.php">Cancel</a></p>
      </div>
    </div>
  </body>
</html>
