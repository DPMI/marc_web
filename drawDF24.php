<?php
include ("jpgraph.php");
include ("jpgraph_log.php");
include ("jpgraph_scatter.php");
include ("jpgraph_line.php");
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

$logx=0;
$logy=0;
$LEN=5;

$LEN=$_GET["LEN"];


for($k=1;$k<$LEN;$k++){
	$idx=sprintf("x$k");
	$idy=sprintf("y$k");
//	print "leX = $idx <= " . $_GET[$idx] . "[EOF] <br>\n";
//	print "leY = $idy <= " . $_GET[$idy] . "[EOF]<br>\n";

	$leX = preg_split("/,/",$_GET[$idx]);
	$leY = preg_split("/,/",$_GET[$idy]);
	$datax[$k]= $leX;
	$datay[$k]= $leY;
}

//print_r($datax);
//print_r($datay);

$zoom=$_GET["zoom"];
$logx=$_GET["logx"];
$logy=$_GET["logy"];

$width=$zoom*400; // Plus 20, 10 to the left, 10 to the right
$heigth=$zoom*150; // Plus 20, 10 to the top and 10 to the bottom

//print "data= [<br>\n";
$sumofY=0.0;
//array_push($datax,$x[1]);
//array_push($datay,0.0);

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
$graph->SetScale($scale);
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
$graph->xaxis->SetTextLabelInterval(3);

$Color=255;
$colorStep=255/$LEN;

for($j=1;$j<$LEN;$j++){
//	print "This data. <br>\n";
//	print "datay=<br>\n";
//	print_r($datay[$j]);
//	print "<br>\ndatax=<br>\n";
//	print_r($datax[$j]);
//	print "<br>\n";
//	$sp[$j]= new ScatterPlot($datay[$j],$datax[$j]);
	$sp[$j]= new LinePlot($datay[$j]);
	
	$theColor=sprintf("#%0XFFFF",$Color);
//	print "Color = $theColor <br>\n";
	$Color-=$colorStep;
	$sp[$j]->SetColor($theColor);
	$sp[$j]->mark->SetType(MARK_FILLEDCIRCLE);
	$sp[$j]->SetWeight(2);
	$sp[$j]->SetLegend(sprintf("-%d",$LEN-$j));
	$graph->Add($sp[$j]);
}

$graph->Stroke();
//$grap->legend

?>


