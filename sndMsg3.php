<?php
require("sessionCheck.php");
require("config.inc");
require("model/MP.php");

$mp = MP::from_id($_GET['id']);
if ( !$mp ){
  die("invalid MP");
}

$alert = null;
$type = $_POST['type'];
$message = $_POST['message'];

if ( !is_numeric($type) ){
  $alert = "Expected a numerical message type.";
} else {
  switch ( (int)$type ){
  case 3:
    if ( !is_numeric($message) ){
      $alert = "Expected a numerical filter id.";
    } else {
      $id = (int)$message;
      $mp->reload_filter($id);
    }
    break;
  default:
    $data = pack("N", $type) . $message;
    $mp->send($data);
  };
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en-US" xml:lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="http://www.bth.se/bth/styles/bth.css" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <link rel="shortcut icon" type="image/x-icon" href="http://www.bth.se/favicon.ico" />
    <title>MArC :: Measurement point control</title>
  </head>

  <body class="bthcss">
    <div id="content">
<?php if ( !$alert ){ ?>
      <div class="notice">
	<p>Message sent. <a href="sendMsg.php?SID=<?=$sid?>"><br/>Back</a>.</p>
      </div>
<?php } else { ?>
      <div class="alert">
	 <p><?=$alert?> <a href="sndMsg2.php?SID=<?=$sid?>&amp;id=<?=$mp->id?>"><br/>Back</a>.</p>
      </div>
<?php } ?>
    </div>
  </body>
</html>
