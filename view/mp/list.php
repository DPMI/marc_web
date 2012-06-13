<?php //  -*- mode:html;  -*- ?>
<h1>Measurement Points</h1>
<table border="0" cellspacing="0" width="100%" class="list">
  <tr>
    <th align="left" valign="bottom">Status</th>
    <th align="left" valign="bottom"><a href="<?=$index?>/MP?order=id&amp;asc=<?=$ascinv?>">ID</a></th>
    <th align="left" valign="bottom"><a href="<?=$index?>/MP?order=name&amp;asc=<?=$ascinv?>">name</a></th>
    <th align="left" valign="bottom"><a href="<?=$index?>/MP?order=ip&amp;asc=<?=$ascinv?>">ip:port</a></th>
    <th align="left" valign="bottom"><a href="<?=$index?>/MP?order=mac&amp;asc=<?=$ascinv?>">mac</a></th>
    <th align="left" valign="bottom"><a href="<?=$index?>/MP?order=comment&amp;asc=<?=$ascinv?>">comment</a></th>
    <th align="left" valign="bottom"><a href="<?=$index?>/MP?order=time&amp;asc=<?=$ascinv?>">last heard from</a></th>
    <th align="left" valign="bottom"><a href="<?=$index?>/MP?order=MAMPid&amp;asc=<?=$ascinv?>">MAMPid</a></th>
    <th align="left" valign="bottom"><a href="<?=$index?>/MP?order=maxFilters&amp;asc=<?=$ascinv?>">Filters</a></th>
    <th align="left" valign="bottom">Control</th>
  </tr>
  
<?php foreach ( $mps as $mp ){ $toggle = 0; ?>
  <tr class="<?=($toggle++ % 2 == 0) ? "even" : "odd"?>">
    <td>
		<?=$mp->status()?>
		<?=$mp->ping()?>
	</td>

<?php if ( $mp->is_authorized() ){ ?>
    <td><a href="<?=$index?>/MP/view/<?=$mp->MAMPid?>"><?=$mp->id?></a></td>
    <td><a href="<?=$index?>/MP/view/<?=$mp->MAMPid?>"><?=$mp->name?></a></td>
<?php } else { ?>
    <td><?=$mp->id?></td>
    <td><?=$mp->name?></td>
<? } ?>
    <td><?=$mp->ip?>:<?=$mp->port?></td>
    <td><?=$mp->mac?></td>
    <td><?=strlen($mp->comment) > 0 ? $mp->comment : "&nbsp;" ?></td>
    <td><?=$mp->time?></td>
    <td><?=$mp->MAMPid?></td>
    <td><a href="<?=$index?>/MP/filter/<?=$mp->MAMPid?>?>"><?=$mp->filter_count()?> / <?=$mp->maxFilters?></a></td>
    <td>
<?php if ( $mp->is_authorized() ){ ?>
      <a href="<?=$root?>control.php?id=<?=$mp->id?>&amp;action=stop">Stop</a>
<?php } else { ?>
      <a href="<?=$root?>authMP.php?id=<?=$mp->id?>">Auth</a>
<?php } ?>
      <a href="<?=$root?>control.php?id=<?=$mp->id?>&amp;action=remove">Remove</a>
    </td>
  </tr>
<?php } /* foreach $mps */ ?>
</table>
