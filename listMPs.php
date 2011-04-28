<?php //  -*- mode:html;  -*- ?>
<?php

require("sessionCheck.php");
require("config.inc");
require_once('model/MP.php');

$order = isset($_GET['order']) ? $_GET['order'] : 'name';
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
    <title>MArC :: Measurement points</title>
  </head>
  
  <body class="bthcss">
    <div id="content">
      <h1>Measurement points</h1>
      <table border="0" cellspacing="0" width="100%">
	<tr>
	  <th valign="bottom">Status</th>
	  <th valign="bottom"><a href="listMPs.php?SID=<?=$sid?>&amp;order=id&amp;asc=<?=$ascinv?>">ID</a></th>
	  <th valign="bottom"><a href="listMPs.php?SID=<?=$sid?>&amp;order=name&amp;asc=<?=$ascinv?>">name</a></th>
	  <th valign="bottom"><a href="listMPs.php?SID=<?=$sid?>&amp;order=ip&amp;asc=<?=$ascinv?>">ip:port</a></th>
	  <th valign="bottom"><a href="listMPs.php?SID=<?=$sid?>&amp;order=mac&amp;asc=<?=$ascinv?>">mac</a></th>
	  <th valign="bottom"><a href="listMPs.php?SID=<?=$sid?>&amp;order=comment&amp;asc=<?=$ascinv?>">comment</a></th>
	  <th valign="bottom"><a href="listMPs.php?SID=<?=$sid?>&amp;order=time&amp;asc=<?=$ascinv?>">time</a></th>
	  <th valign="bottom"><a href="listMPs.php?SID=<?=$sid?>&amp;order=MAMPid&amp;asc=<?=$ascinv?>">MAMPid</a></th>
	  <th valign="bottom"><a href="listMPs.php?SID=<?=$sid?>&amp;order=maxFilters&amp;asc=<?=$ascinv?>">max<br/>filters</a></th>
	  <th valign="bottom">Authorize MP</th>
	  <th valign="bottom">Control</th>
	</tr>
	
<?php foreach ( $mps as $mp ){ ?>
	<tr class="<?=($toggle++ % 2 == 0) ? "even" : "odd"?>">
	  <td><?=$mp->status()?></td>
	  <td><?=$mp->id?></td>
	  <td><?=$mp->name?></td>
	  <td><?=$mp->ip?>:<?=$mp->port?></td>
	  <td><?=$mp->mac?></td>
	  <td><?=strlen($mp->comment) > 0 ? $mp->comment : "&nbsp;" ?></td>
	  <td><?=$mp->time?></td>
	  <td><?=$mp->MAMPid?></td>
	  <td><?=$mp->maxFilters?></td>
<?php if ( $mp->is_authorized() ){ ?>
	  <td>Authorized</td>
<?php } else { ?>
	  <td><a href="authMP.php?SID=<?=$sid?>&amp;id=<?=$mp->id?>">Auth</a></td>
<?php } ?>
	  <td>
	    <a href="control.php?id=<?=$mp->id?>&amp;action=stop">Stop</a>
	    <a href="control.php?id=<?=$mp->id?>&amp;action=remove">Remove</a>
	  </td>
	</tr>
<?php } /* foreach $mps */ ?>
      </table>
    </div>
  </body>
</html>
