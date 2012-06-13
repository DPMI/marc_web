<?php
include ("jpgraph.php");
include ("jpgraph_bar.php");
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

$x=$_GET["legend"]; // Comma separated. i.e. drawPDF.php?x=1,5.6,9&y=0.1,0.2,1
$y=$_GET["values"]; // Comma separated
$zoom=$_GET["zoom"];
$title=$_GET["title"];

$width=$zoom*350; // Plus 20, 10 to the left, 10 to the right
$heigth=$zoom*150; // Plus 20, 10 to the top and 10 to the bottom

//print "$x <-> $y <br>\n";

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
for($k=1;$k<sizeof($x);$k++){
	array_push($datay,$y[$k]);
//	print "x[k] = " . $x[$k] . "<br>\n";
	array_push($datax,sprintf("%f", $x[$k]));
}
//print_r($datax);

$graph=new Graph($width, $heigth, "auto");
$graph->img->SetMargin(40,50,20,120);
$graph->SetShadow();
$graph->SetScale("textlin");

//Set X-labels
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetLabelAngle(90);

$graph->title->Set("$title");
$graph->title->SetFont(FF_FONT1,FS_BOLD);

$sp1= new BarPlot($datay);
$graph->Add($sp1);
$graph->Stroke();


?>


