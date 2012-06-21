<?php

require("sessionCheck.php");
require("config.php");

$path = array('');
if ( isset($_SERVER['PATH_INFO']) ){
  $path = explode('/', rtrim($_SERVER['PATH_INFO'],'/'));
  array_shift($path);
}
$handler = array_shift($path);

$controller = null;
$content = null;
if ( file_exists("controller/$handler.php") ){
  require("controller/$handler.php");
  $classname = "{$handler}Controller";
  $controller = new $classname();

  try {
    echo $controller->_path($path);
  } catch( Exception $e ){
	  echo $e;
  }
} else {
	echo "404";
}
