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

function get_param($key, $default=null){
	return isset($_GET[$key]) ? $_GET[$key] : $default;
}

function clamp($value, $min, $max){
	return max(min($value, $max), $min);
}

$mampid = get_param('mampid');
$what = get_param('what');
$ci = get_param('CI', false);
$span = get_param('span', '24h');
$width = clamp(get_param('width', -1), -1, 2000);
$height = clamp(get_param('height', -1), -1, 2000);
$cache = get_param('cache', 1) == 1;
$mp = MP::from_mampid($mampid);

/* calculate image size */
$aspect = 1.7;
if ( $width == -1 && $height == -1 ){
	$width = 345;
	$height = (int)($width / $aspect);
} else if ( $width == -1 && $height != -1 ){
	$width = (int)($height * $aspect);
} else if ( $width != -1 && $height == -1 ){
	$height = (int)($width / $aspect);
}

if ( !$mp ){
	error($width, $height, array("Parameter error", "Missing or invalid MAMPid"));
}

if ( !in_array($what, array('packets', 'bu') ) ){
	error($width, $height, array("Parameter error", "Missing or invalid graph type"));
}



$filebase = "$mampid";
$title = "$mp->name ($span)";
$iface = explode(';', $mp->CI_iface);
if ( $ci !== false ){
	$x = $iface[$ci];
  $filebase = "{$mampid}_$x";
  $title = "{$mp->name} $x ($span)";
}

$regen = true;
$filename = '/tmp/marcweb_' . md5(implode('_', array($filebase, $what, $span, $width, $height))) . '.png';
if ( $cache ){
	$stat = @stat($filename);
	$regen = $stat == false || (time() - $stat['mtime'] > 5*60);
}

if ( $regen ){
  $argv = array(
    "rrdtool", "graph",
    $filename,
    "-a", "PNG",
    "--full-size-mode", "--width", $width, "--height", $height,
    "--title", $title,
    "--start", "end-$span",
	  );
  if ( $what == 'packets' ){
	  $argv = array_merge($argv, array(
		                      "--vertical-label", "pkt/sec",
		                      "DEF:total=$rrdbase/$filebase.rrd:total:AVERAGE", "VDEF:total_last=total,TOTAL",
		                      "DEF:matched=$rrdbase/$filebase.rrd:matched:AVERAGE", "VDEF:matched_last=matched,TOTAL",
		                      "DEF:dropped=$rrdbase/$filebase.rrd:dropped:AVERAGE", "VDEF:dropped_last=dropped,TOTAL",
		                      "CDEF:discarded=total,matched,-,dropped,-", "VDEF:discarded_last=discarded,TOTAL",
		                      "AREA:dropped#ff0000:Dropped\:   :",        "GPRINT:dropped_last:%12.0lf pkts\l",
		                      "AREA:discarded#ffff00:Discarded\: :STACK", "GPRINT:discarded_last:%12.0lf pkts\l",
		                      "AREA:matched#00ff00:Matched\:   :STACK",   "GPRINT:matched_last:%12.0lf pkts\l",
		                      "LINE1:total#000000:Total\:     :",         "GPRINT:total_last:%12.0lf pkts\l",));

  } else if ( $what == 'bu' ){
	  $argv = array_merge($argv, array(
		                      "--vertical-label", "Utilization (%)",
		                      "-l", "0", "-u", "100",
		                      "DEF:BU=$rrdbase/$filebase.rrd:BU:MAX",
		                      "VDEF:BU_last=BU,LAST",
		                      "VDEF:BU_min=BU,MINIMUM",
		                      "VDEF:BU_max=BU,MAXIMUM",
		                      "VDEF:BU_avg=BU,AVERAGE",
		                      "VDEF:BU_95=BU,95,PERCENTNAN",

		                      "CDEF:bue=BU,00,LE,BU,00,IF",
		                      "CDEF:bu0=BU,00,GT,BU,10,GT,10,BU,00,-,IF,UNKN,IF",
		                      "CDEF:bu1=BU,10,GT,BU,20,GT,10,BU,10,-,IF,UNKN,IF",
		                      "CDEF:bu2=BU,20,GT,BU,30,GT,10,BU,20,-,IF,UNKN,IF",
		                      "CDEF:bu3=BU,30,GT,BU,40,GT,10,BU,30,-,IF,UNKN,IF",
		                      "CDEF:bu4=BU,40,GT,BU,50,GT,10,BU,40,-,IF,UNKN,IF",
		                      "CDEF:bu5=BU,50,GT,BU,60,GT,10,BU,50,-,IF,UNKN,IF",
		                      "CDEF:bu6=BU,60,GT,BU,70,GT,10,BU,60,-,IF,UNKN,IF",
		                      "CDEF:bu7=BU,70,GT,BU,80,GT,10,BU,70,-,IF,UNKN,IF",
		                      "CDEF:bu8=BU,80,GT,BU,90,GT,10,BU,80,-,IF,UNKN,IF",
		                      "CDEF:bu9=BU,90,GT,BU,90,-,UNKN,IF",

		                      "AREA:bu0#00ff00::STACK",
		                      "AREA:bu1#19e100::STACK",
		                      "AREA:bu2#32af00::STACK",
		                      "AREA:bu3#4b9600::STACK",
		                      "AREA:bu4#647d00::STACK",
		                      "AREA:bu5#7d6400::STACK",
		                      "AREA:bu6#964b00::STACK",
		                      "AREA:bu7#af3200::STACK",
		                      "AREA:bu8#e11900::STACK",
		                      "AREA:bu9#ff0000::STACK",
		                      "LINE2:BU_95#00000055:",

		                      "COMMENT:Buffer utilization ", "GPRINT:BU_last:%3.1lf%%\l",
		                      "COMMENT:95th percentile    ", "GPRINT:BU_95:%3.1lf%%\l",
		                      "COMMENT:Min", "GPRINT:BU_min:%3.1lf%%",
		                      "COMMENT:Max", "GPRINT:BU_max:%3.1lf%%",
		                      "COMMENT:Avg", "GPRINT:BU_avg:%3.1lf%%\l"
		                      ));
  }

  $cmd = "'" . implode("' '", $argv) . "'";
  exec("$cmd 2>&1", $output, $rc);
  if ( $rc != 0 ){
	  error($width, $height, array_merge(
		        array("RRDtool error code $rc"),
		        explode("\n", wordwrap($cmd, floor($width / imagefontwidth(2)), "\\\n", true)),
		        array("Output:"),
		        explode("\n", wordwrap(implode("\n", $output), floor($width / imagefontwidth(2)), "\\\n", true))
		        ));
  }
}

header("Content-Disposition: inline; filename=\"{$filebase}_{$what}\"");
header("Content-type: image/png");
echo file_get_contents($filename);
if ( !$cache ) unlink($filename);

?>
