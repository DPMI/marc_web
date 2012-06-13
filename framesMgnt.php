<?php
require("sessionCheck.php");
require("config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html lang="en-US" xml:lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
  <head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="shortcut icon" type="image/x-icon" href="http://www.bth.se/favicon.ico" />
   <title>Network Performance Lab -- MArC</title>
  </head>

  <frameset rows="100,*,60" border="1">
    <frame src="header.php" name="header" scrolling="no" noresize="noresize" />
    <frameset cols="200,*">
      <frame src="frameMngtMenu.php" name="index1" />
      <frame name="view" />
    </frameset>
    <frame src="bottom.php" name="bottom" scrolling="no" noresize="noresize" />
  </frameset>

</html>
