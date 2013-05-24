<?php //  -*- mode:html;  -*- ?>
<?php 
require_once('helper/age.php'); 
require_once('model/MP.php');

$order = isset($_GET['order']) ? $_GET['order'] : 'name';
$asc = isset($_GET['asc']) ? (int)$_GET['asc'] : 1;
$ascinv = 1 - $asc;
$toggle=0;

$mps = MP::selection(array(
    '@order' => "$order" . ($asc ? '' : ':desc')
));

?>

<h1>DPMI Measurement Area Control Daemon</h1>
<?php if ( isset($_SESSION['passwd_warning']) ){ ?>
<div class="notice" style="border: 1px dashed black; background-position:left center;">
  <p>Your password must be updated. The hashing algorithm changed in 0.7 due to security issues. Your current password will continue to work in this version but future versions may remove this compatability.</p>
  <p>The password can be updated on the <a href="<?=$index?>/account/self">account</a> page.</p>
</div>
<?php } ?>
<h1>Messages</h1>


<h1>Measurement Points</h1>
<table border="0" cellspacing="0" width="100%" class="list">
  <tr>
    <th align="left" valign="bottom">Status</th>
    <th align="left" valign="bottom"><a href="<?=$index?>/MP?order=MAMPid&amp;asc=<?=$ascinv?>">MAMPid</a></th> 
    <th align="left" valign="bottom"><a href="<?=$index?>/MP?order=ip&amp;asc=<?=$ascinv?>">ip</a></th>
    <th align="left" valign="bottom"><a href="<?=$index?>/MP?order=time&amp;asc=<?=$ascinv?>">last heard from</a></th>
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

    <td><?=$mp->MAMPid?></td>
    <td><?=$mp->ip?></td>
    <td><?=age($mp->time)?></td>

    <td><?=$mp->mtu?></td>
    <td><?=$mp->sync($mp->MAMPid)?></td>
    <td><?=$mp->version?></td>


  </tr>
<?php } /* foreach $mps */ ?>
</table>

