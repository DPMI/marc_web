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
    <link rel="stylesheet" type="text/css" href="http://www.bth.se/bth/styles/bth.css" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <link rel="shortcut icon" type="image/x-icon" href="http://www.bth.se/favicon.ico" />
    <title>MArC :: Measurement point control</title>
  </head>

  <body class="bthcss">
    <div id="content">
      <table border="0" width="100%">
	<tr>
	  <th><a href="sendMsg.php?SID=<?=$sid?>&amp;order=id&amp;asc=<?=$ascinv?>">ID</a></th>
	  <th><a href="sendMsg.php?SID=<?=$sid?>&amp;order=name&amp;asc=<?=$ascinv?>">name</a></th>
	  <th><a href="sendMsg.php?SID=<?=$sid?>&amp;order=ip&amp;asc=<?=$ascinv?>">ip</a></th>
	  <th><a href="sendMsg.php?SID=<?=$sid?>&amp;order=port&amp;asc=<?=$ascinv?>">port</a></th>
	  <th><a href="sendMsg.php?SID=<?=$sid?>&amp;order=mac&amp;asc=<?=$ascinv?>">mac</a></th>
	  <th><a href="sendMsg.php?SID=<?=$sid?>&amp;order=comment&amp;asc=<?=$ascinv?>">comment</a></th>
	  <th><a href="sendMsg.php?SID=<?=$sid?>&amp;order=time&amp;asc=<?=$ascinv?>">time</a></th>
	  <th><a href="sendMsg.php?SID=<?=$sid?>&amp;order=MAMPid&amp;asc=<?=$ascinv?>">MAMPid</a></th>
	  <th><a href="sendMsg.php?SID=<?=$sid?>&amp;order=maxFilters&amp;asc=<?=$ascinv?>">maxFilters</a></th>
	  <th>Send to MP</th>
	</tr>
	
<?php foreach ( $mps as $mp ){ ?>
<?php $color = ($toggle++ % 2 == 0) ? "CCC" : "DDD"; ?>
	<tr style="background: #<?=$color?>;">
	  <td><?=$mp->id?></td>
	  <td><?=$mp->name?></td>
	  <td><?=$mp->ip?></td>
	  <td><?=$mp->port?></td>
	  <td><?=$mp->mac?></td>
	  <td><?=strlen($mp->comment) > 0 ? $mp->comment : "&nbsp;" ?></td>
	  <td><?=$mp->time?></td>
	  <td><?=$mp->MAMPid?></td>
	  <td><?=$mp->maxFilters?></td>
	  <td><a href="sndMsg2.php?SID=<?=$sid?>&amp;id=<?=$mp->id?>">SendTo</a></td>
	</tr>
<?php } /* foreach $mps */ ?>
      </table>
    </div>
  </body>
</html>
