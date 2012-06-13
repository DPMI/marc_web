<?php //  -*- mode:html;  -*- ?>
<?php
require("sessionCheck.php");

if ( isset($_SESSION['config_error']) ){ /* redirected */
  $config_error = $_SESSION['config_error'];
} else {
  require('config.php');
}

unset($_SESSION['config_error']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en-US" xml:lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <link rel="shortcut icon" type="image/x-icon" href="http://www.bth.se/favicon.ico" />
    <title>MArC :: Configuration error</title>
  </head>

  <body>
    <div id="content">
<?php if ( count($config_error) > 0 ){ ?>
      <h1>MArC Configuration error</h1>
<?php foreach ($config_error as $err){ ?>
      <div class="alert">
	<p><?=$err['message']?></p>
	<p>
<?php foreach ($err as $key => $value){ if ( $key == "message" ){ continue; } ?>
	  <?=$key?>: <?=$value?><br/>
<?php } /* foreach $key => $value */ ?>
	</p>
      </div>
<?php } /* foreach $config_error */ ?>
<?php } else { /* if ( count > 0 ) */ ?>
      <h1>MArC Configuration</h1>
      <div class="notice">
	<p>Configuration OK</p>
	<p><a href="<?=$root?>">Return</a></p>
      </div>
<?php } /* if ( count > 0 ) */ ?>
    </div>
  </body>
</html>
