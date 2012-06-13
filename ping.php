<?php

require("sessionCheck.php");
require("config.php");

if ( !$use_ping ){
	trigger_error('ping.php referenced without having ping enabled in config');
	exit;
}

/* get mampid */
if ( !isset($_GET['MAMPid']) ){
	die("no mampid set");
}
$mampid = $_GET['MAMPid'];

/* allow cache for 5 min */
$age = 5;
header('Cache-Control: max-age=' . $age * 60 . ',public, must-revalidate');
header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + $age * 60));
header_remove('Pragma');

/* get ip of MP */
$stmt = $db->prepare("SELECT ip, port FROM measurementpoints WHERE mampid=?");
$stmt->bind_param('s', $mampid);
$stmt->bind_result($ip, $port);
$stmt->execute();
if ( !$stmt->fetch() ){
	die("no such mp $mampid");
}
$stmt->close();

/* ping host (half ugly solution found on the web) */
$group = 4;
$tB = microtime(true);
$fp = @fsockopen("udp://$ip", $port, $errno, $errstr, 500);
if ( $fp ){
	$tA = microtime(true);
	$ping = round((($tA - $tB) * 1000), 0);
	if ( $ping < 40 ) $group = 1;
	else if ( $ping < 150 ) $group = 2;
	else $group = 3;
	fclose($fp);
}

/* tell browser to expect png image */
header('Content-Type: image/png');

$im = imagecreatefrompng("gfx/ping_bar_{$group}.png");
imagealphablending($im, false);
imagesavealpha($im, true);
imagepng($im);
imagedestroy($im);
