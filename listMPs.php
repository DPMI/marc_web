<?

require("sessionCheck.php");
require("config.inc");
require_once('model/MP.php');

$order = isset($_GET['order']) ? $_GET['order'] : 'id';
$asc = isset($_GET['asc']) ? (int)$_GET['asc'] : 1;
$ascinv = 1 - $asc;
$toggle=0;

$mps = MP::selection(array(
    '@order' => "$order" . ($asc ? '' : ':desc')
));

?>
<html>
<?=$pageStyle?>

<table border="0">
  <tr>
    <th>Status</th>
    <th><a href="listMPs.php?SID=<?=$sid?>&amp;order=id&amp;asc=<?=$ascinv?>">ID</th>
    <th><a href="listMPs.php?SID=<?=$sid?>&amp;order=name&amp;asc=<?=$ascinv?>">name</a></th>
    <th><a href="listMPs.php?SID=<?=$sid?>&amp;order=ip&amp;asc=<?=$ascinv?>">ip</a></th>
    <th><a href="listMPs.php?SID=<?=$sid?>&amp;order=port&amp;asc=<?=$ascinv?>">ip</a></th>
    <th><a href="listMPs.php?SID=<?=$sid?>&amp;order=mac&amp;asc=<?=$ascinv?>">mac</a></th>
    <th><a href="listMPs.php?SID=<?=$sid?>&amp;order=comment&amp;asc=<?=$ascinv?>">comment</a></th>
    <th><a href="listMPs.php?SID=<?=$sid?>&amp;order=time&amp;asc=<?=$ascinv?>">time</a></th>
    <th><a href="listMPs.php?SID=<?=$sid?>&amp;order=MAMPid&amp;asc=<?=$ascinv?>">MAMPid</a></th>
    <th><a href="listMPs.php?SID=<?=$sid?>&amp;order=maxFilters&amp;asc=<?=$ascinv?>">maxFilters</a></th>
    <th>Authorize MP</th>
  </tr>

<?php foreach ( $mps as $mp ){ ?>
<?php $color = ($toggle++ % 2 == 0) ? "CCC" : "DDD"; ?>
  <tr style="background: #<?=$color?>;">
    <td><?=$mp->status()?></td>
    <td><?=$mp->id?></td>
    <td><?=$mp->name?></td>
    <td><?=$mp->ip?></td>
    <td><?=$mp->port?></td>
    <td><?=$mp->mac?></td>
    <td><?=strlen($mp->comment) > 0 ? $mp->comment : "&nbsp;" ?></td>
    <td><?=$mp->time?></td>
    <td><?=$mp->MAMPid?></td>
    <td><?=$mp->maxFilters?></td>
    <?php if ( $mp->is_authorized() ){ ?>
    <td>Authorized</td>
    <? } else { ?>
    <td><a href="authMP.php?SID=<?=$sid?>&amp;id=<?=$mp->id?>">Auth</a></td>
    <? } ?>
  </tr>
<?php } /* foreach $mps */ ?>
</table>

</body></html>
