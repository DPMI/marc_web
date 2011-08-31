<?php

require("sessionCheck.php");
require("config.php");
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

$verify = array(
  'remove' => "Are you sure you want to remove measurement point \"{$mp->name}\" and all its data?<br/>This action is irreversable.",
  'stop' => "Are you sure you want to stop the measurement point \"{$mp->name}\"? It is not possible to restart it using the webgui."
);

$need_verify = array_key_exists($action, $verify) && !isset($_GET['verify']);

if ( !$need_verify ){
  if ( $action == 'remove' ){
    $mp->delete();
  } else if ( $action == 'stop' ){
    $mp->stop();
  } else {
    die("unknown action");
  }

  header("Location: ${index}/MP");
  exit;
}

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
	<p><?=$verify[$action]?></p>
	<p><a href="?id=<?=$id?>&amp;action=<?=$action?>&verify=1"><?=$action?></a>&nbsp;&nbsp;&nbsp;<a href="listMPs.php">cancel</a></p>
      </div>
    </div>
  </body>
</html>
