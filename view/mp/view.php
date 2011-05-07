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
    <th>Status</th>
    <td><b><?=$mp->status()?></b></td>
  </tr>
  <tr>
    <th>ID</th>
    <td><a href="<?=$index?>/MP/view/<?=$mp->MAMPid?>"><?=$mp->id?></a></td>
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
</table>
