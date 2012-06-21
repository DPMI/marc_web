<?php //  -*- mode:html;  -*- ?>
<?php require_once('helper/age.php'); ?>
<h1><a href="<?=$index?>/MP">Measurement Points</a> &gt; <?=$mp->name?></h1>
<p>
  Comment: <?=strlen($mp->comment) > 0 ? $mp->comment : '<span style="color: #ccc;">(unset)</span>' ?><br/>
  Last heard from: <?=$mp->time?> (<?=age($mp->time)?>)
</p>
<style>
  th { text-align: right; }
</style>
<table>
  <tr>
    <th>ID</th>
    <td><?=$mp->id?></td>
  </tr>
  <tr>
    <th>Status</th>
    <td><?=$mp->status()?></td>
  </tr>
  <tr>
    <th>IP:Port</th>
    <td><?=$mp->ip?>:<?=$mp->port?></td>
  </tr>
  <tr>
    <th>hwaddr</th>
    <td><?=$mp->mac?></td>
  </tr>
  <tr>
  </tr>
  <tr>
    <th>MAMPid</th>
    <td><?=$mp->MAMPid?></td>
  </tr>
  <tr>
    <th><a href="<?=$index?>/MP/filter/<?=$mp->MAMPid?>">Filters</a></th>
    <td><?=$mp->filter_count()?> of <?=$mp->maxFilters?> filters present.</td>
  </tr>
  <tr>
    <th>CI</th>
    <td><?=$mp->noCI?> (<?=$mp->ifaces()?>)</td>
  </tr>
  <tr>
    <th>Drivers</th>
    <td><?=$mp->drivers_str()?></td>
  </tr>
  <tr>
    <th>Versions</th>
    <td><?=$mp->version_str()?></td>
  </tr>
</table>

<h2>Overview</h2>
<p>(Graphs are updated at a 5min interval)</p>
<img src="<?=$root?>graph.php?mampid=<?=$mp->MAMPid?>&amp;span=24h" />
<img src="<?=$root?>graph.php?mampid=<?=$mp->MAMPid?>&amp;span=60d" />

<h2>Capture Interfaces</h2>
<?php for ($ci=0; $ci < $mp->noCI; $ci++){ ?>
<img src="<?=$root?>graph.php?mampid=<?=$mp->MAMPid?>&amp;CI=<?=$ci?>&amp;span=24h" />
<img src="<?=$root?>graph.php?mampid=<?=$mp->MAMPid?>&amp;CI=<?=$ci?>&amp;span=60d" />
<br/>
<?php } ?>
