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
//header ("Content-type: image/png");

$x=$_GET["x"]; // Comma separated. i.e. drawPDF.php?x=1,5.6,9&y=0.1,0.2,1
$y=$_GET["y"]; 
$zoom=isset($_GET["zoom"]) ? $_GET["zoom"] : 1.0;
$logx=0;
$logx=isset($_GET["log"]) ? $_GET["log"] : 0;

$width=$zoom*250; // Plus 20, 10 to the left, 10 to the right
$heigth=$zoom*75; // Plus 20, 10 to the top and 10 to the bottom
$xZero=20;   // Size top,left is 0,0(gd) we normalize it..
$yZero=$heigth+20;



$x = preg_split ("/,/", $x);
$y = preg_split ("/,/", $y);


if (sizeof($x) != sizeof($y) ) {
	print "Error: sizeof(x) isnt the same as sizeof(y) !<br>\n";
	print "sizeof(x) = " . sizeof($x) . "<br>\n";
	print "sizeof(y) = " . sizeof($y) . "<br>\n";
	exit;
}


$maxX=0;
$maxY=0;
$minX=10000000;
foreach ($x as $val){
	if($logx==1) {
		$val=log($val);
	}
	if( $maxX < $val) {
		$maxX=$val;
	}
	if ($minX > $val) {
		$minX=$val;	
	}
}

foreach ($y as $val){
	if( $maxY < $val) {
		$maxY=$val;
	}	
}
$xScaleFactor=$maxX/($width);
if($zoom==1) {
if($xScaleFactor>1) {
	$xScaleFactor=1/$xScaleFactor;
}
} else {
if($xScaleFactor<1) {
	$xScaleFactor=1/$xScaleFactor;
}
	
}
$yScaleFactor=$maxY/$heigth;
if($yScaleFactor>1) {
	$yScaleFactor=1/$yScaleFactor;
}
$data=array();

//print "data= [<br>\n";
for($k=0;$k<sizeof($x);$k++){
	if($logx==1){
		array_push($data,($xZero+$xScaleFactor*log($x[$k])),($yZero-round($heigth*$y[$k])));		

	}
	else {
		array_push($data,($xZero+$xScaleFactor*$x[$k]),($yZero-round($heigth*$y[$k])));		
	}
}
//print "sizeof data= " . sizeof($data) . "<br>\n";

$im=imageCreate($width+40,$heigth+40);
$background= imageColorAllocate($im, 230,230,230);
$blue=imagecolorallocate($im,0,0,255); 
$black=imagecolorallocate($im,0,0,0); 
imageline($im,$xZero-5,$yZero,$width+20,$yZero,$blue); // Xlinjen
imageline($im,$xZero,$heigth+25,$xZero,10,$blue); // Ylinjen!!!

$maxCnt=sizeof($data)/2-1;
//print "maxCnt=  $maxCnt <br>\n";
for($k=0;$k<2*$maxCnt;$k=$k+2){
	imageline($im,$data[$k],$data[$k+1],$data[$k+2],$data[$k+3],$black);
//	print "(" . $data[$k] .",". $data[$k+1] . ")-(" . $data[$k+2] ."," . $data[$k+3] .")<br>\n";
}

$background_color = imagecolorallocate ($im, 255, 255, 255);
$text_color = imagecolorallocate ($im, 233, 14, 91);
imagestringup ($im, $zoom*1, 2, $zoom*70,  "P(X<x)", $text_color);
imagestring($im, $zoom*1, 2, 5,  "$maxY", $text_color);
imageline($im,$xZero-5,10,$xZero+5,10,$text_color);
imagestring ($im, $zoom*1, $zoom*160, $yZero+5,  "Minutes", $text_color);

imageline($im,$xZero+$width,$yZero-5,$xZero+$width,$yZero+5,$blue);

imagestring($im, $zoom*1, $width+20, $yZero+5,  "$minX", $text_color);
imagestring($im, $zoom*1, $xZero-15, $yZero+5,  "-$maxX", $text_color);
imageline($im, 1*($width-$xZero)/6, $yZero+2, 1*($width-$xZero)/6, $yZero-2,$blue);
imagestring($im, $zoom*1, 1*($width-$xZero)/6-15, $yZero+5, "-300", $text_color);

imageline($im, 2*($width-$xZero)/6, $yZero+2, 2*($width-$xZero)/6, $yZero-2,$blue);
imagestring($im, $zoom*1, 2*($width-$xZero)/6-15, $yZero+5, "-240", $text_color);

imageline($im, 3*($width-$xZero)/6, $yZero+2, 3*($width-$xZero)/6, $yZero-2,$blue);
imagestring($im, $zoom*1, 3*($width-$xZero)/6-15, $yZero+5, "-180", $text_color);

imageline($im, 4*($width-$xZero)/6, $yZero+2, 4*($width-$xZero)/6, $yZero-2,$blue);
imagestring($im, $zoom*1, 4*($width-$xZero)/6-15, $yZero+5, "-120", $text_color);

imageline($im, 5*($width-$xZero)/6, $yZero+2, 5*($width-$xZero)/6, $yZero-2,$blue);
imagestring($im, $zoom*1, 5*($width-$xZero)/6-15, $yZero+5, "-60", $text_color);


imagepng ($im);

 
imagePng($im);
//imageDestroy($im);


?>


