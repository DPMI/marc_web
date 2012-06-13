<?php
include ("jpgraph.php");
include ("jpgraph_log.php");
include ("jpgraph_scatter.php");
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
//header ("Content-type: image/png");
//header ("Content-type: image/png");

$x1=$_GET["x1"]; // Comma separated. i.e. drawPDF.php?x=1,5.6,9&y=0.1,0.2,1
$y1=$_GET["y1"];
$x2=$_GET["x2"]; // Comma separated. i.e. drawPDF.php?x=1,5.6,9&y=0.1,0.2,1
$y2=$_GET["y2"];

$zoom=$_GET["zoom"];
$logx=0;
$logx=$_GET["logx"];
$logy=0;
$logy=$_GET["logy"];
$str1=$_GET["str1"];
$str2=$_GET["str2"];


//$logx=1;
//$logy=0;
$width=$zoom*250; // Plus 20, 10 to the left, 10 to the right
$heigth=$zoom*100; // Plus 20, 10 to the top and 10 to the bottom

$x1 = preg_split ("/,/", $x1);
$y1 = preg_split ("/,/", $y1);
$x2 = preg_split ("/,/", $x2);
$y2 = preg_split ("/,/", $y2);


if (sizeof($x1) != sizeof($y1) ) {
	print "Error: sizeof(x) isnt the same as sizeof(y) !<br>\n";
	print "sizeof(x) = " . sizeof($x1) . "<br>\n";
	print "sizeof(y) = " . sizeof($y1) . "<br>\n";
	exit;
}
if (sizeof($x2) != sizeof($y2) ) {
	print "Error: sizeof(x) isnt the same as sizeof(y) !<br>\n";
	print "sizeof(x) = " . sizeof($x2) . "<br>\n";
	print "sizeof(y) = " . sizeof($y2) . "<br>\n";
	exit;
}
$datax1=array();
$datay1=array();
$datax2=array();
$datay2=array();

$maxX=0;
$minX=10;

//print "data= [<br>\n";
$sumofY1=0.0;
for($k=0;$k<sizeof($x1);$k++){
	if($x1[$k]>$maxX)
	  $maxX=$x1[$k];
	if($x1[$k]<$minX)
	  $minX=$x1[$k];
	array_push($datax1,$x1[$k]);
	$sumofY1=$sumofY1+$y1[$k];
//	array_push($datay1,$sumofY1);
	array_push($datay1,$y1[$k]);
//	print "[$k] x1= " . $x1[$k] . " y1 = " . $sumofY1 . "<br>\n";
}
//print "data= [<br>\n";
$sumofY2=0.0;
for($k=0;$k<sizeof($x2);$k++){
	if($x2[$k]>$maxX)
	  $maxX=$x2[$k];
	if($x2[$k]<$minX)
	  $minX=$x2[$k];
	array_push($datax2,$x2[$k]);
	$sumofY2=$sumofY2+$y2[$k];
//	array_push($datay2,$sumofY2);
	array_push($datay2,$y2[$k]);
//	print "[$k] x2= " . $x2[$k] . " y2 = " . $sumofY2 . "<br>\n";
}

$graph=new Graph($width, $heigth, "auto");

if($logx==0){
	$scale="lin";
} else {
	$scale="log";
}
if($logy==0){
	$scale=$scale."lin";
} else {
	$scale=$scale."log";
}
//print "x1[0] = " . $x1[0] . " x1[1] - x1[0] = " . ($x1[1]-$x1[0]) . "<br>\n";

$graph->SetScale($scale, 0, 1, 0.9*$minX, 1.1*$maxX);
//$graph->xaxis->scale->ticks->Set(2,10);
$graph->img->SetMargin(40,40,40,40);
$graph->SetShadow();
$title = $_GET["title"];
$xlabel= $_GET["xlabel"];
$ylabel= $_GET["ylabel"];

$graph->title->Set("$title");
$graph->xaxis->title->Set("$xlabel");
$graph->yaxis->title->Set("$ylabel");
$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->ygrid->Show(true,true);
$graph->xgrid->Show(true,true);


$sp1= new ScatterPlot($datay1,$datax1);
//$sp1->SetLinkPoints(true,"red",2);
$sp1->mark->SetType(MARK_CIRCLE);
$sp1->SetImpuls();
//$sp1->mark->SetFillColor("navy");
//$sp1->mark->SetWidth(3);
$sp1->value->Show();
$sp1->SetLegend(sprintf("%s", $str1));
$graph->Add($sp1);

$sp2= new ScatterPlot($datay2,$datax2);
//$sp2->SetLinkPoints(true,"blue",2);
$sp2->mark->SetType(MARK_SQUARE);
$sp2->SetImpuls();
//$sp2->mark->SetFillColor("navy");
//$sp2->mark->SetWidth(3);
$sp2->value->Show();
$sp2->SetLegend(sprintf("%s", $str2));
$graph->Add($sp2);

$graph->Stroke();


?>


