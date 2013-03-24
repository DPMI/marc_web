<?php //  -*- mode:html;  -*- ?>
<?php require_once('helper/age.php'); ?>
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
  <tr class="<?=($toggle++ % 2 == 0) ? "even" : "odd"?>" data-id="<?=$mp->id?>" data-name="<?=$mp->name?>" data-mampid="<?=$mp->MAMPid?>">
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
<?php } ?>
    <td><?=$mp->ip?>:<?=$mp->port?></td>
    <td><?=$mp->mac?></td>
    <td><?=strlen($mp->comment) > 0 ? $mp->comment : "&nbsp;" ?></td>
    <td><?=age($mp->time)?></td>
    <td><?=$mp->MAMPid?></td>
	<td>
<?php if ( $mp->is_authorized() ){ ?>
    <a href="<?=$index?>/MP/filter/<?=$mp->MAMPid?>"><?=$mp->filter_count()?> / <?=$mp->maxFilters?></a>
<?php } ?>
	</td>
    <td>
<?php if ( $mp->is_authorized() ){ ?>
      <a href="<?=$index?>/MP/stop/<?=$mp->id?>" class="stop_mp">Stop</a>
<?php } else { ?>
      <a href="<?=$root?>authMP.php?id=<?=$mp->id?>">Auth</a>
<?php } ?>
      <a href="<?=$index?>/MP/delete/<?=$mp->id?>" class="remove_mp">Remove</a>
    </td>
  </tr>
<?php } /* foreach $mps */ ?>
</table>

<h1>Software and Time</h1>
<table border="0" cellspacing="0" width="100%" class="list">
  <tr>
    <th align="left" valign="bottom">Status</th>
    <th align="left" valign="bottom"><a href="<?=$index?>/MP?order=id&amp;asc=<?=$ascinv?>">ID</a></th>
    <th align="left" valign="bottom"><a href="<?=$index?>/MP?order=name&amp;asc=<?=$ascinv?>">name</a></th>
    <th align="left" valign="bottom"><a href="<?=$index?>/MP?order=MAMPid&amp;asc=<?=$ascinv?>">MAMPid</a></th>
    <th align="left" valign="bottom"><a href="<?=$index?>/MP?order=MAMPid&amp;asc=<?=$ascinv?>">MTU</a></th>
    <th align="left" valign="bottom"><a href="<?=$index?>/MP?order=time&amp;asc=<?=$ascinv?>">Time Synchronization</a></th>
    <th align="left" valign="bottom"><a href="<?=$index?>/MP?order=time&amp;asc=<?=$ascinv?>">Software Versions</a></th>


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
<?php } ?>
    <td><?=$mp->MAMPid?></td>
    <td><?=$mp->mtu?></td>
    <td><?=$mp->sync($mp->MAMPid)?></td>
    <td><?=$mp->version?></td>

  </tr>
<?php } /* foreach $mps */ ?>
</table>
