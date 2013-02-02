<?php

require("sessionCheck.php");
require("config.php");
require('model/MP.php');

function error($width, $height, $data){
	$im = imagecreate($width, $height);
	$bg = imagecolorallocate($im, 255, 255, 255);
	$fg = imagecolorallocate($im, 0, 0, 0);
	$dy = 15;
	foreach ( $data as $i => $line ){
		$size = $i == 0 ? 5 : 2;
		imagestring($im, $size, 5,  $i*$dy+5, $line, $fg);
	}
	header('Content-type: image/png');
	imagepng($im);
	imagedestroy($im);
	exit;
}

$mampid = $_GET['mampid'];
$ci = isset($_GET['CI']) ? $_GET['CI'] : false;
$span = isset($_GET['span']) ? $_GET['span'] : '24h';
$mp = MP::from_mampid($mampid);
/**@todo handle all error conditions */

chdir($rrdbase);

$filebase = "$mampid";
$title = "$mp->name ($span)";
$iface = explode(';', $mp->CI_iface);
if ( $ci !== false ){
	$x = $iface[$ci];
  $filebase = "{$mampid}_$x";
  $title = "{$mp->name} $x ($span)";
}

$filename = "{$filebase}_{$span}.png";
$regen = true;
$stat = @stat($filename);
$regen = $stat == false || (time() - $stat['mtime'] > 5*60);

if ( $regen ){
  $argv = array(
    "rrdtool", "graph",
    "$filename",
    "-a", "PNG",
    "--title", $title,
    "--vertical-label", "pkt/sec",
    "--start", "end-$span",
    "DEF:total=$filebase.rrd:total:AVERAGE", "VDEF:total_last=total,TOTAL",
    "DEF:matched=$filebase.rrd:matched:AVERAGE", "VDEF:matched_last=matched,TOTAL",
    "LINE1:total#ff0000:Received:",
    "GPRINT:total_last:%.0lf pkts",
    "AREA:matched#00ff00:Matched",
    "GPRINT:matched_last:%.0lf pkts"
  );
  $cmd = "'" . implode("' '", $argv) . "'";
  exec("$cmd 2>&1", $output, $rc);
  if ( $rc != 0 ){
	  error(497, 173, array_merge(
		        array("RRDtool error code $rc"),
		        explode("\n", wordwrap($cmd, floor(497 / imagefontwidth(2)), "\\\n", true)),
		        array("Output:", implode("\n",$output))));
  }
}

header ("Content-type: image/png");
echo file_get_contents($filename);

?>