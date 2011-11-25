<?php

require("sessionCheck.php");
require("config.php");

if ( !$use_ping ){
	trigger_error('ping.php referenced without having ping enabled in config');
	exit;
}

if ( !isset($_GET['MAMPid']) ){
	die("no mampid set");
}

/* allow cache for 5 min */
$age = 5;
header('Cache-Control: max-age=' . $age * 60 . ',public, must-revalidate');
header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + $age * 60));
header_remove('Pragma');

/* tell browser to expect png image */
header('Content-Type: image/png');

$im = imagecreatefrompng("./".$root."gfx/ping_bar_1.png");
imagealphablending($im, false);
imagesavealpha($im, true);
imagepng($im);
imagedestroy($im);
