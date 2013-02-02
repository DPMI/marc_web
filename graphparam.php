<?php
require("sessionCheck.php");
require("config.php");
?>
<!DOCTYPE html>
<html>
<head>
	<title><?=$_GET['mampid']?> -- MArC</title>
    <script type="text/javascript" src="<?=$root?>js/jquery-1.8.3.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('#start').focus();
		});
	</script>
</head>
<body>
<form method="get">
	<img src="<?=$root?>graph.php?<?=htmlentities($_SERVER['QUERY_STRING'])?>&amp;width=640&amp;cache=0" />
	<input type="hidden" name="mampid" value="<?=htmlentities($_GET['mampid'])?>" />
	<input type="hidden" name="what" value="<?=htmlentities($_GET['what'])?>" />
<?php if ( isset($_GET['CI']) ){ ?>
	<input type="hidden" name="CI" value="<?=htmlentities($_GET['CI'])?>" />
<?php } ?>
	<label for="start">Start: </label><input type="text" id="start" name="start" value="<?=htmlentities($_GET['start'])?>" size="8"/>
	<label for="end">End: </label><input type="text" id="end" name="end" value="<?=htmlentities($_GET['end'])?>" size="8" />
	<input type="submit" value="Update" />
</form>
<div>
	<p>A variety of suffixes like "min", "weeks" and "months" is accepted, see <tt>rrdfetch(1)</tt> for details.</p>
</div>
</body>
</html>
