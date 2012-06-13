<?php //  -*- mode:html;  -*- ?>
<?php
require("sessionCheck.php");
require("config.php");
require_once('model/MP.php');

$mps = MP::selection();
$toggle = 0;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en-US" xml:lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <link rel="shortcut icon" type="image/x-icon" href="http://www.bth.se/favicon.ico" />
    <title>MArC :: Filters</title>
  </head>

  <body class="bthcss">
    <div id="content">
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
    </div>
  </body>
</html>
