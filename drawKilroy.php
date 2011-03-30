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

/*
	print "x1= $x1<br>\n";
	print "x2= $x2<br>\n";
	print "y1= $y1<br>\n";
	print "y2= $y2<br>\n";
*/

$zoom=$_GET["zoom"];
$logx=0;
$logx=$_GET["logx"];
$logy=0;
$logy=$_GET["logy"];

//$logx=1;
//$logy=0;
$width=$zoom*400; // Plus 20, 10 to the left, 10 to the right
$heigth=$zoom*150; // Plus 20, 10 to the top and 10 to the bottom

$x1 = preg_split ("/,/", $x1);
$y1 = preg_split ("/,/", $y1);
$x2 = preg_split ("/,/", $x2);
$y2 = preg_split ("/,/", $y2);


if (sizeof($x1) != sizeof($y1) ) {
	print "Error: sizeof(x) isnt the same as sizeof(y) !<br>\n";
	print "sizeof(x1) = " . sizeof($x1) . "<br>\n";
	print "sizeof(y1) = " . sizeof($y1) . "<br>\n";
	exit;
}
if (sizeof($x2) != sizeof($y2) ) {
	print "Error: sizeof(x) isnt the same as sizeof(y) !<br>\n";
	print "sizeof(x2) = " . sizeof($x2) . "<br>\n";
	print "sizeof(y2) = " . sizeof($y2) . "<br>\n";
	exit;
}
if (sizeof($x1) != sizeof($x2) ) {
	print "Error: sizeof(x) isnt the same as sizeof(y) !<br>\n";
	print "sizeof(x1) = " . sizeof($x1) . "<br>\n";
	print "sizeof(x2) = " . sizeof($x2) . "<br>\n";
	exit;
}


$datax=array();
$datay=array();

//print "data= [<br>\n";
$sumofY=0.0;

for($k=0;$k<sizeof($x1)-1;$k++){
	$yvalue=$y1[$k]-$y2[$k];

	array_push($datax,$x1[$k]);
	array_push($datay,$yvalue);
//	print "x= " . $x1[$k] . " y = " . $yvalue . "<br>\n";

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
//$graph->xaxis->SetTextLabelInterval(3);

$sp1= new ScatterPlot($datay,$datax);
$sp1->SetLinkPoints(true,"red",2);
$sp1->mark->SetType(MARK_FILLEDCIRCLE);
$sp1->mark->SetFillColor("navy");
$sp1->mark->SetWidth(3);
$graph->Add($sp1);


$graph->Stroke();


?>


