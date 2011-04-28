<?php //  -*- mode:html;  -*- ?>
<?php
require("sessionCheck.php");
require("config.inc");
require_once('model/MP.php');

$order = isset($_GET['order']) ? $_GET['order'] : 'id';
$asc = isset($_GET['asc']) ? (int)$_GET['asc'] : 1;
$ascinv = 1 - $asc;
$toggle=0;

$mps = MP::selection(array(
    '@order' => "$order" . ($asc ? '' : ':desc')
));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en-US" xml:lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <link rel="shortcut icon" type="image/x-icon" href="http://www.bth.se/favicon.ico" />
    <title>MArC :: Measurement point control</title>
  </head>

  <body class="bthcss">
    <div id="content">
      <h1>Measurement points</h1>
      <table border="0" cellspacing="0" width="100%">
	<tr>
	  <th><a href="sendMsg.php?SID=<?=$sid?>&amp;order=id&amp;asc=<?=$ascinv?>">ID</a></th>
	  <th><a href="sendMsg.php?SID=<?=$sid?>&amp;order=name&amp;asc=<?=$ascinv?>">name</a></th>
	  <th><a href="sendMsg.php?SID=<?=$sid?>&amp;order=ip&amp;asc=<?=$ascinv?>">ip:port</a></th>
	  <th><a href="sendMsg.php?SID=<?=$sid?>&amp;order=mac&amp;asc=<?=$ascinv?>">mac</a></th>
	  <th><a href="sendMsg.php?SID=<?=$sid?>&amp;order=comment&amp;asc=<?=$ascinv?>">comment</a></th>
	  <th><a href="sendMsg.php?SID=<?=$sid?>&amp;order=MAMPid&amp;asc=<?=$ascinv?>">MAMPid</a></th>
	  <th>Send to MP</th>
	</tr>
	
<?php foreach ( $mps as $mp ){ ?>
	<tr class="<?=($toggle++ % 2 == 0) ? "even" : "odd"?>">
	  <td><?=$mp->id?></td>
	  <td><?=$mp->name?></td>
	  <td><?=$mp->ip?>:<?=$mp->port?></td>
	  <td><?=$mp->mac?></td>
	  <td><?=strlen($mp->comment) > 0 ? $mp->comment : "&nbsp;" ?></td>
	  <td><?=$mp->MAMPid?></td>
	  <td><a href="sndMsg2.php?SID=<?=$sid?>&amp;id=<?=$mp->id?>">SendTo</a></td>
	</tr>
<?php } /* foreach $mps */ ?>
      </table>
    </div>
  </body>
</html>
