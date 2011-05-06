<h1>Filters</h1>

<?php if ( count($mps) == 0 ){ ?>
<p>No MPs found!</p>
<?php } else { ?>
<?php foreach ( $mps as $mp ){ ?>
<h2><?=$mp->name?></h2>

<table border="0" cellspacing="0" width="100%">
  <tr>
    <th width="50">Filter ID</th>
    <th>Filter Description</th>
    <th width="300">Consumer Information</th>
  </tr>
  
<?php foreach ( $mp->filters() as $filter ){ ?>
  <tr class="<?=($toggle++ % 2 == 0) ? "even" : "odd"?>">
    <td><?=$filter->filter_id?></td>
    <td><?=$filter->description()?></td>
    <td><?=$filter->destination_description()?></td>
  </tr>
<?php } /* foreach $filter */ ?>
</table>
<?php } /* foreach $mps */ ?>
<?php } /* if count($mps) */ ?>
