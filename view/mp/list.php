<?php //  -*- mode:html;  -*- ?>
<h1>Measurement points</h1>
<table border="0" cellspacing="0" width="100%">
  <tr>
    <th align="left" valign="bottom">Status</th>
    <th align="left" valign="bottom"><a href="<?=$root?>index2.php/MP?order=id&amp;asc=<?=$ascinv?>">ID</a></th>
    <th align="left" valign="bottom"><a href="<?=$root?>index2.php/MP?order=name&amp;asc=<?=$ascinv?>">name</a></th>
    <th align="left" valign="bottom"><a href="<?=$root?>index2.php/MP?order=ip&amp;asc=<?=$ascinv?>">ip:port</a></th>
    <th align="left" valign="bottom"><a href="<?=$root?>index2.php/MP?order=mac&amp;asc=<?=$ascinv?>">mac</a></th>
    <th align="left" valign="bottom"><a href="<?=$root?>index2.php/MP?order=comment&amp;asc=<?=$ascinv?>">comment</a></th>
    <th align="left" valign="bottom"><a href="<?=$root?>index2.php/MP?order=time&amp;asc=<?=$ascinv?>">time</a></th>
    <th align="left" valign="bottom"><a href="<?=$root?>index2.php/MP?order=MAMPid&amp;asc=<?=$ascinv?>">MAMPid</a></th>
    <th align="left" valign="bottom"><a href="<?=$root?>index2.php/MP?order=maxFilters&amp;asc=<?=$ascinv?>">max<br/>filters</a></th>
    <th align="left" valign="bottom">Control</th>
  </tr>
  
<?php foreach ( $mps as $mp ){ ?>
  <tr class="<?=($toggle++ % 2 == 0) ? "even" : "odd"?>">
    <td><?=$mp->status()?></td>
    <td><a href="<?=$index?>/MP/view/<?=$mp->id?>"><?=$mp->id?></a></td>
    <td><a href="<?=$index?>/MP/view/<?=$mp->id?>"><?=$mp->name?></a></td>
    <td><?=$mp->ip?>:<?=$mp->port?></td>
    <td><?=$mp->mac?></td>
    <td><?=strlen($mp->comment) > 0 ? $mp->comment : "&nbsp;" ?></td>
    <td><?=$mp->time?></td>
    <td><?=$mp->MAMPid?></td>
    <td><?=$mp->maxFilters?></td>
    <td>
<?php if ( $mp->is_authorized() ){ ?>
      <a href="control.php?id=<?=$mp->id?>&amp;action=stop">Stop</a>
<?php } else { ?>
      <a href="authMP.php?id=<?=$mp->id?>">Auth</a>
<?php } ?>
      <a href="control.php?id=<?=$mp->id?>&amp;action=remove">Remove</a>
    </td>
  </tr>
<?php } /* foreach $mps */ ?>
</table>