<?php
/*
drawPDF.php  -- Copyright Patrik Carlsson (patrik.carlsson@bth.se) 2002
Inputs. 
	x - a string with comma separated values. 
	y - a string with comma separated values.
	zoom - a zoom factor. (Only integers!!! 1,2,3,4)
example:
http://trantor/phpDemo/drawPDF.php?x=0,100,200,300,350,5000&y=0.0,1.0,0.5,0.75,0.9,1.0&zoom=3
Output
	PNG image. 
example:
<img src="drawPDF.php?x=0,100,200,300&y=0.0,0.5,0.75,1.0&zoom=1">


*/

header ("Content-type: image/png");

$scale  = isset($_GET["scale"]) ? $_GET["scale"] : 1.0;
$margin = 15 * $scale;
$padding = 15 * $scale;
$width  = $scale * 250 + $margin * 2;
$height = $scale * 75 + $margin * 2;
$fw = imagefontwidth($scale);
$fh= imagefontheight($scale);

$xmin = $padding;
$xmax = $width - $padding;
$ymax = $padding;
$ymin = $height - $padding;

$im=imageCreate($width, $height);
$background= imageColorAllocate($im, 230,230,230);
$blue=imagecolorallocate($im,0,0,255); 
$black=imagecolorallocate($im,0,0,0); 
$background_color = imagecolorallocate ($im, 255, 255, 255);
$text_color = imagecolorallocate ($im, 233, 14, 91);

function gd_die($str){
  global $im, $padding, $black;
  imagestring ($im, 5, $padding, $padding, $str, $black);
  imagepng ($im);
  exit;
}

ob_start();

require("sessionCheck.php");
require("config.inc");
require_once('model/MP.php');
require_once('model/MPStatus.php');

$mpid = isset($_GET['mpid']) ? $_GET['mpid'] : null;
$CI = isset($_GET['CI']) ? $_GET['CI'] : null;

if ( $mpid == null || $CI == null ){
  gd_die("must set mpid($mpid) and CI");
}

$mp = MP::from_mampid($mpid);

if ( !$mp ){
  gd_die("invalid mp");
}

$stats = $mp->stats(360);
$max = $stats->max_delta_matched_pkts();

imageline($im, $xmin, $ymin, $xmax, $ymin, $blue);
imageline($im, $xmin, $ymin, $xmin, $ymax, $blue);
//imageline($im, $padding, $height - $padding , $padding, $padding,$blue); // Ylinjen!!!

$y_series = $stats->matched_pkts();
$x_series = range(0, count($y_series)-1);
$count = count($x_series);
$x_scale = ($xmax-$xmin) / max($x_series);
$y_scale = ($ymin-$ymax) / max($y_series);

for ( $i = 0; $i < $count-1; $i++ ){
  imageline($im,
	    $xmin + $x_series[$i] * $x_scale,
	    $ymin - $y_series[$i] * $y_scale,
	    $xmin + $x_series[$i+1] * $x_scale,
	    $ymin - $y_series[$i+1] * $y_scale,
	    $black);
}

/* $maxCnt=sizeof($data)/2-1; */
/* //print "maxCnt=  $maxCnt <br>\n"; */
/* for($k=0;$k<2*$maxCnt;$k=$k+2){ */
/* 	imageline($im,$data[$k],$data[$k+1],$data[$k+2],$data[$k+3],$black); */
/* //	print "(" . $data[$k] .",". $data[$k+1] . ")-(" . $data[$k+2] ."," . $data[$k+3] .")<br>\n"; */
/* } */

imagestringup ($im, $scale*1, 5*$scale, $height*0.5+8,  "P(X<x)", $text_color);
imagestring($im, $scale*1, $padding-$fw, $padding-$fh, "1.0", $text_color);
/* imageline($im,$xZero-5,10,$xZero+5,10,$text_color); */
/* imagestring ($im, $zoom*1, $zoom*160, $yZero+5,  "Minutes", $text_color); */

/* imageline($im,$xZero+$width,$yZero-5,$xZero+$width,$yZero+5,$blue); */

/* imagestring($im, $zoom*1, $width+20, $yZero+5,  "$minX", $text_color); */
/* imagestring($im, $zoom*1, $xZero-15, $yZero+5,  "-$maxX", $text_color); */
/* imageline($im, 1*($width-$xZero)/6, $yZero+2, 1*($width-$xZero)/6, $yZero-2,$blue); */
/* imagestring($im, $zoom*1, 1*($width-$xZero)/6-15, $yZero+5, "-300", $text_color); */

/* imageline($im, 2*($width-$xZero)/6, $yZero+2, 2*($width-$xZero)/6, $yZero-2,$blue); */
/* imagestring($im, $zoom*1, 2*($width-$xZero)/6-15, $yZero+5, "-240", $text_color); */

/* imageline($im, 3*($width-$xZero)/6, $yZero+2, 3*($width-$xZero)/6, $yZero-2,$blue); */
/* imagestring($im, $zoom*1, 3*($width-$xZero)/6-15, $yZero+5, "-180", $text_color); */

/* imageline($im, 4*($width-$xZero)/6, $yZero+2, 4*($width-$xZero)/6, $yZero-2,$blue); */
/* imagestring($im, $zoom*1, 4*($width-$xZero)/6-15, $yZero+5, "-120", $text_color); */

/* imageline($im, 5*($width-$xZero)/6, $yZero+2, 5*($width-$xZero)/6, $yZero-2,$blue); */
/* imagestring($im, $zoom*1, 5*($width-$xZero)/6-15, $yZero+5, "-60", $text_color); */

$content = ob_get_contents();
ob_end_clean();

$n = 0;
foreach( explode("\n", $content) as $line ){
  imagestring($im, 2, 0, $n++ * 12, $line, $black);
}

imagepng ($im);

//imageDestroy($im);


?>


