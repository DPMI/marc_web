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

$x=$HTTP_GET_VARS["x"]; // Comma separated. i.e. drawPDF.php?x=1,5.6,9&y=0.1,0.2,1
$y=$HTTP_GET_VARS["y"]; 
$xtimes=1;
$ytimes=1;
if(is_scalar($HTTP_GET_VARS["xtimes"])) {
	$xtimes=$HTTP_GET_VARS["xtimes"];	
}
if(is_scalar($HTTP_GET_VARS["ytimes"])) {
	$ytimes=$HTTP_GET_VARS["ytimes"];	
}
$zoom=$HTTP_GET_VARS["zoom"];
$logx=0;
$logx=$HTTP_GET_VARS["logx"];
$logy=0;
$logy=$HTTP_GET_VARS["logy"];

//$logx=1;
//$logy=0;
$width=$zoom*300; // Plus 20, 10 to the left, 10 to the right
$heigth=$zoom*120; // Plus 20, 10 to the top and 10 to the bottom

$x = preg_split ("/,/", $x);
$y = preg_split ("/,/", $y);


if (sizeof($x) != sizeof($y) ) {
	print "Error: sizeof(x) isnt the same as sizeof(y) !<br>\n";
	print "sizeof(x) = " . sizeof($x) . "<br>\n";
	print "sizeof(y) = " . sizeof($y) . "<br>\n";
	exit;
}
$datax=array();
$datay=array();

//print "data= [<br>\n";
$sumofY=0.0;
for($k=0;$k<sizeof($x);$k++){
	if($x1[$k]>$maxX) 
	  $maxX=$x[$k]*$xtimes;
	if($x1[$k]<$minX) 
	  $minX=$x[$k]*$xtimes;
	array_push($datax,$x[$k]*$xtimes);
	$sumofY=$sumofY+$y[$k]*$ytimes;
	array_push($datay,$sumofY);		
//	array_push($datay,$y[$k]);
//	print "x= " . $x[$k] . " y = " . $sumofY . "<br>\n";

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

//$graph->SetScale($scale);
$graph->SetScale($scale, 0, 1, 0.9*$minX, 1.1*$maxX);
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

$sp1= new ScatterPlot($datay,$datax);
$sp1->SetLinkPoints(true,"red",2);
$sp1->mark->SetType(MARK_FILLEDCIRCLE);
$sp1->mark->SetFillColor("navy");
$sp1->mark->SetWidth(3);
$graph->Add($sp1);


$graph->Stroke();


?>


