<?php
require("sessionCheck.php");
require("config.inc");
require_once('model/MP.php');

$mps = MP::selection();
$toggle = 0;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en-US" xml:lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="http://www.bth.se/bth/styles/bth.css" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <link rel="shortcut icon" type="image/x-icon" href="http://www.bth.se/favicon.ico" />
    <title>MArC :: Filters</title>
  </head>
  
  <body class="bthcss">
<?php if ( count($mps) == 0 ){ ?>
    <p>No MPs found!</p>
<?php } else { ?>
<?php foreach ( $mps as $mp ){ ?>
    <table border="1">
      <tr style="background: #eee;">
	<th colspan="14"><?=$mp->name?></th>
	<th>
	  <a href="verifyFilters.php?SID=<?=$sid?>&amp;MAMPid=<?=$mp->MAMPid?>"><img width="12" height="13" border="0" alt="Verify all filters" src="button_properties.png" /></a>
	  <a href="addFilter.php?SID=<?=$sid?>"><img width="12" height="13"  border="0" alt="Add filter" src="button_insert.png" /></a>
	</th>
      </tr>
      
      <tr>
	<th>Index</th>
	<th>Filter_ID</th>
	<th>CI</th>
	<th>VLAN_TCI/<br/>MASK</th>
	<th>ETH_TYPE/<br/>MASK</th>
	<th>ETH_SRC/<br/>MASK</th>
	<th>ETH_DST/<br/>MASK</th>
	<th>IP_PROTO</th>
	<th>IP_SRC/<br/>MASK</th>
	<th>IP_DST/<br/>MASK</th>
	<th>SRC_PORT/<br/>MASK</th>
	<th>DST_PORT/<br/>MASK</th>
	<th>DESTADDR/TYPE</th>
	<th>CAPLEN</th>
	<th>&nbsp;</th>
      </tr>
      
<?php foreach ( $mp->filters() as $filter ){ ?>
<?php $color = ($toggle++ % 2 == 0) ? "AAA" : "BBB"; ?>
      <tr style="background: #<?=$color?>;">
	<td><?=$filter->ind?></td>
	<td><?=$filter->filter_id?></td>
	<td><?=$filter->CI_ID?></td>
	<td><?=$filter->VLAN_TCI?><br/><?=$filter->VLAN_TCI_MASK?></td>
	<td><?=$filter->ETH_TYPE?>/<br/><?=$filter->ETH_TYPE_MASK?></td>
	<td><?=$filter->ETH_SRC?>/<br/><?=$filter->ETH_SRC_MASK?></td>
	<td><?=$filter->ETH_DST?>/<br/><?=$filter->ETH_DST_MASK?></td>
	<td><?=$filter->IP_PROTO?></td>
	<td><?=$filter->IP_SRC?>/<br/><?=$filter->IP_SRC_MASK?></td>
	<td><?=$filter->IP_DST?>/<br/><?=$filter->IP_DST_MASK?></td>
	<td><?=$filter->SRC_PORT?>/<br/><?=$filter->SRC_PORT_MASK?></td>
	<td><?=$filter->DST_PORT?>/<br/><?=$filter->DST_PORT_MASK?></td>
	<td><?=$filter->DESTADDR?>/<?=$filter->TYPE?></td>
	<td><?=$filter->CAPLEN?></td>
	<td>
	  <a href="editFilter.php?SID=<?=$sid?>&amp;filter_id=<?=$filter->filter_id?>&amp;MAMPid=<?=$mp->MAMPid?>"><img width="12" height="13"  border="0" alt='Edit' src='button_edit.png'/></a>
	  <a href="delFilter.php?SID=<?=$sid?>&amp;filter_id=<?=$filter->filter_id?>&amp;MAMPid=<?=$mp->MAMPid?>"><img width="12" height="13"  border="0" alt='Drop' src='button_drop.png'/></a>
	  <a href="verifyFilter.php?SID=<?=$sid?>&amp;filter_id=<?=$filter->filter_id?>&amp;MAMPid=<?=$mp->MAMPid?>"><img width="12" height="13"  border="0" alt='Verify' src='button_properties.png'/></a>
	</td>
      </tr>
<?php } /* foreach $filter */ ?>
    </table>
<?php } /* foreach $mps */ ?>
<?php } /* if count($mps) */ ?>

  </body>
</html>
