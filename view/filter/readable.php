<?php global $u_access ?>
<h1>Filters . </h1>

<?php if ( count($mps) == 0 ){ ?>
<p>No MPs found!</p>
<?php } else { ?>
<?php foreach ( $mps as $mp ){ ?>
<h2><a href="<?=$index?>/MP/view/<?=$mp->MAMPid?>"><?=$mp->name?></a></h2>

<table border="0" cellspacing="0" width="100%" class="list">
  <tr>
    <th width="50">Filter ID</th>
    <th>Filter Description</th>
    <th width="300">Consumer Information</th>
  </tr>
<?php $toggle = 0; ?>
<?php foreach ( $mp->all_filters() as $filter ){ ?>
  <tr class="<?=($toggle++ % 2 == 0) ? "even" : "odd"?>">
<?php if ( $u_access >= 1 ) { ?>
    <td><a href="<?=$index?>/MP/filter/<?=$mp->MAMPid?>/<?=$filter->filter_id?>"><?=$filter->filter_id?></a></td>
<?php } else { ?>
    <td><?=$filter->filter_id?></td>
<?php } ?>
    <td><?=$filter->description()?></td>
    <td><?=$filter->destination_description()?></td>
  </tr>
<?php } /* foreach $filter */ ?>
</table>
<?php } /* foreach $mps */ ?>
<?php } /* if count($mps) */ ?>
