<?php //  -*- mode:html;  -*- ?>
<h1><?=$mp->name?></h1>
<p>
  <?=strlen($mp->comment) > 0 ? $mp->comment : "&nbsp;" ?><br/>
  Last heard from: <?=$mp->time?>
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
    <th>Max filters</th>
    <td><?=$mp->maxFilters?></td>
  </tr>
  <tr>
    <th>CI</th>
    <td><?=$mp->noCI?> (br0 [PLACEHOLDER])</td>
  </tr>
  <tr>
    <th>Drivers</th>
    <td>raw, pcap [PLACEHOLDER]</td>
  </tr>
  <tr>
    <th>Versions</th>
    <td>caputils-0.7.1 libmarc-0.7.0 mp-0.7.0 [placeholder]</td>
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
