<?php

require("sessionCheck.php");
require("config.php");

if ( !$use_ping ){
	trigger_error('ping.php referenced without having ping enabled in config');
	exit;
}

/**
 * Try to establish a connection to MP and send a ping event.
 * @return 1-4 where 4 is unreachable and 1 is RTT < 40.
 */
function ping($ip, $port, $mampid){
	$MP_CONTROL_PING_EVENT = 133;

	$tB = microtime(true);
	$fp = fsockopen("udp://$ip", $port, $errno, $errstr, 2);
	if ( !$fp ){
		return 4;
	}
	stream_set_timeout($fp, 2);

	if ( fwrite($fp, pack("Na16", $MP_CONTROL_PING_EVENT, $mampid)) === FALSE ){
		return 4;
	}

	if ( ($data=fread($fp, 4+16)) === FALSE ){
		return 4;
	}
	if ( ($data=@unpack("Ntype/a16mampid", $data)) === FALSE ){
		return 4;
	}
	if ( !($data['type'] == $MP_CONTROL_PING_EVENT && strcmp($data['mampid'], $mampid) == 0) ){
		return 4;
	}

	$tA = microtime(true);
	fclose($fp);

	$ping = round((($tA - $tB) * 1000), 0);
	if ( $ping < 40 ) return 1;
	else if ( $ping < 150 ) return 2;
	else return 3;
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

$group = ping($ip, $port, $mampid);

/* tell browser to expect png image */
header('Content-Type: image/png');

$im = imagecreatefrompng("gfx/ping_bar_{$group}.png");
imagealphablending($im, false);
imagesavealpha($im, true);
imagepng($im);
imagedestroy($im);
