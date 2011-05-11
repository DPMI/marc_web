<?php //  -*- mode:html;  -*- ?>
<?php
require("sessionCheck.php");
require("config.php");
require_once('model/MP.php');

$mps = MP::selection();
$toggle = 0;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en-US" xml:lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <link rel="shortcut icon" type="image/x-icon" href="http://www.bth.se/favicon.ico" />
    <title>MArC :: Filters</title>
  </head>
  
  <body class="bthcss">
    <div id="content">
      <h1>Filters</h1>
      <?php if ( count($mps) == 0 ){ ?>
      <p>No MPs found!</p>
      <?php } else { ?>
      <?php foreach ( $mps as $mp ){ ?>
      <h2>
	<?=$mp->name?>
	<a href="verifyFilters.php?MAMPid=<?=$mp->MAMPid?>"><img width="12" height="13" border="0" alt="Verify all filters" title="Verify all filters" src="button_properties.png" /></a>
	<a href="editFilter.php?MAMPid=<?=$mp->MAMPid?>"><img width="12" height="13"  border="0" alt="Add filter" title="Add filter" src="button_insert.png" /></a>
      </h2>

      <table border="0" cellspacing="0" width="100%">
	<tr>
	  <th valign="bottom">Filter ID</th>
	  <th valign="bottom">Capture<br/>interface</th>
	  <th valign="bottom">VLAN TCI/<br/>MASK</th>
	  <th valign="bottom">ETH type/<br/>MASK</th>
	  <th valign="bottom">ETH SRC/MASK</th>
	  <th valign="bottom">ETH DST/MASK</th>
	  <th valign="bottom">IP<br/>protocol</th>
	  <th valign="bottom">IP SRC/<br/>MASK</th>
	  <th valign="bottom">IP DST/<br/>MASK</th>
	  <th valign="bottom">SRC PORT/<br/>MASK</th>
	  <th valign="bottom">DST PORT/<br/>MASK</th>
	  <th valign="bottom">Destination</th>
	  <th valign="bottom">Capture<br/>length</th>
	  <th valign="bottom">&nbsp;</th>
	</tr>
      
<?php foreach ( $mp->filters() as $filter ){ ?>
	<tr class="<?=($toggle++ % 2 == 0) ? "even" : "odd"?>">
	  <td valign="top"><?=$filter->filter_id?></td>
	  <td valign="top"><?=$filter->ind & 512 ? $filter->CI_ID : '-' ?></td>
	  <td valign="top"><?=$filter->ind & 256 ? "$filter->VLAN_TCI<br/>/$filter->VLAN_TCI_MASK" : '-' ?></td>
	  <td valign="top"><?=$filter->ind & 128 ? "$filter->ETH_TYPE<br/>/$filter->ETH_TYPE_MASK" : '-' ?></td>
	  <td valign="top"><?=$filter->ind &  64 ? "$filter->ETH_SRC<br/>/$filter->ETH_SRC_MASK"   : '-' ?></td>
	  <td valign="top"><?=$filter->ind &  32 ? "$filter->ETH_DST<br/>/$filter->ETH_DST_MASK"   : '-' ?></td>
	  <td valign="top"><?=$filter->ind &  16 ? $filter->IP_PROTO : '-' ?></td>
	  <td valign="top"><?=$filter->ind &   8 ? "$filter->IP_SRC<br/>/$filter->IP_SRC_MASK"     : '-' ?></td>
	  <td valign="top"><?=$filter->ind &   4 ? "$filter->IP_DST<br/>/$filter->IP_DST_MASK"	   : '-' ?></td>
	  <td valign="top"><?=$filter->ind &   2 ? "$filter->SRC_PORT<br/>/$filter->SRC_PORT_MASK" : '-' ?></td>
	  <td valign="top"><?=$filter->ind &   1 ? "$filter->DST_PORT<br/>/$filter->DST_PORT_MASK" : '-' ?></td>
	  <td valign="top"><?=$filter->DESTADDR?>/<?=$filter->TYPE?></td>
	  <td valign="top"><?=$filter->CAPLEN?></td>
	  <td width="45">
	    <a href="editFilter.php?filter_id=<?=$filter->filter_id?>&amp;MAMPid=<?=$mp->MAMPid?>"><img width="12" height="13"  border="0" alt="Edit" title="Edit filter" src='button_edit.png'/></a>
	    <a href="delFilter.php?filter_id=<?=$filter->filter_id?>&amp;MAMPid=<?=$mp->MAMPid?>"><img width="12" height="13"  border="0" alt="Drop" title="Drop filter" src='button_drop.png'/></a>
	    <a href="verifyFilter.php?filter_id=<?=$filter->filter_id?>&amp;MAMPid=<?=$mp->MAMPid?>"><img width="12" height="13"  border="0" alt="Verify" title="Verify filter" src='button_properties.png'/></a>
	  </td>
	</tr>
<?php } /* foreach $filter */ ?>
      </table>
<?php } /* foreach $mps */ ?>
<?php } /* if count($mps) */ ?>
    </div>
  </body>
</html>
