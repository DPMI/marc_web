<?php //  -*- mode:html;  -*- ?>
<?php require_once('helper/age.php'); ?>
<script type="text/javascript">
$(document).ready(function(){
	$('#comment').editable('<?=$ajax?>/MP/comment/<?=$mp->MAMPid?>', {
		tooltip: 'Click to edit',
		loadurl: '<?=$ajax?>/MP/comment/<?=$mp->MAMPid?>',
		style: 'inherit',
		width: 300,
	});
});
</script>
<h1><a href="<?=$index?>/MP">Measurement Points</a> &gt; <?=$mp->name?></h1>
<p>
  Comment: <span id="comment" style="min-width: 300px;"><?=strlen($mp->comment) > 0 ? $mp->comment : "(unset)" ?></span><br/>
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
    <th title="MTU on MA network">MTU</th>
    <td><?=$mp->mtu >= 0 ? $mp->mtu : 'N/A'?></td>
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
<p>(Graphs are updated at a <?=round($graph_max_age/60,2)?> minute interval)</p>
<a href="<?=$root?>graphparam.php?mampid=<?=$mp->MAMPid?>&amp;start=-24h&amp;end=now&amp;what=packets" target="_blank" onclick="window.open($(this).attr('href'), 'marc_graph','height=480,width=660,menubar=0,resizable=1,toolbar=0').focus(); return false;"><img src="<?=$root?>graph.php?mampid=<?=$mp->MAMPid?>&amp;span=24h&amp;what=packets" /></a>
<a href="<?=$root?>graphparam.php?mampid=<?=$mp->MAMPid?>&amp;start=-6w&amp;end=now&amp;what=packets"  target="_blank" onclick="window.open($(this).attr('href'), 'marc_graph','height=480,width=660,menubar=0,resizable=1,toolbar=0').focus(); return false;"><img src="<?=$root?>graph.php?mampid=<?=$mp->MAMPid?>&amp;span=6w&amp;what=packets"  /></a>

<h2>Capture Interfaces</h2>
<?php for ($ci=0; $ci < $mp->noCI; $ci++){ ?>
<a href="<?=$root?>graphparam.php?mampid=<?=$mp->MAMPid?>&amp;CI=<?=$ci?>&amp;start=-24h&amp;end=now&amp;what=packets" target="_blank" onclick="window.open($(this).attr('href'), 'marc_graph','height=480,width=660,menubar=0,resizable=1,toolbar=0').focus(); return false;"><img src="<?=$root?>graph.php?mampid=<?=$mp->MAMPid?>&amp;CI=<?=$ci?>&amp;span=24h&amp;what=packets" /></a>
<a href="<?=$root?>graphparam.php?mampid=<?=$mp->MAMPid?>&amp;CI=<?=$ci?>&amp;start=-6w&amp;end=now&amp;what=packets"  target="_blank" onclick="window.open($(this).attr('href'), 'marc_graph','height=480,width=660,menubar=0,resizable=1,toolbar=0').focus(); return false;"><img src="<?=$root?>graph.php?mampid=<?=$mp->MAMPid?>&amp;CI=<?=$ci?>&amp;span=6w&amp;what=packets"  /></a>
<a href="<?=$root?>graphparam.php?mampid=<?=$mp->MAMPid?>&amp;CI=<?=$ci?>&amp;start=-24h&amp;end=now&amp;what=bu"      target="_blank" onclick="window.open($(this).attr('href'), 'marc_graph','height=480,width=660,menubar=0,resizable=1,toolbar=0').focus(); return false;"><img src="<?=$root?>graph.php?mampid=<?=$mp->MAMPid?>&amp;CI=<?=$ci?>&amp;span=24h&amp;what=bu"      /></a>
<br/>
<?php } ?>
