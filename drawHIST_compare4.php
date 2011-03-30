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

$x1=$HTTP_GET_VARS["x1"]; // Comma separated. i.e. drawPDF.php?x=1,5.6,9&y=0.1,0.2,1
$y1=$HTTP_GET_VARS["y1"];
$x2=$HTTP_GET_VARS["x2"]; // Comma separated. i.e. drawPDF.php?x=1,5.6,9&y=0.1,0.2,1
$y2=$HTTP_GET_VARS["y2"];

$x3=$HTTP_GET_VARS["x3"]; // Comma separated. i.e. drawPDF.php?x=1,5.6,9&y=0.1,0.2,1
$y3=$HTTP_GET_VARS["y3"];
$x4=$HTTP_GET_VARS["x4"]; // Comma separated. i.e. drawPDF.php?x=1,5.6,9&y=0.1,0.2,1
$y4=$HTTP_GET_VARS["y4"];


 
$zoom=$HTTP_GET_VARS["zoom"];
$logx=0;
$logx=$HTTP_GET_VARS["logx"];
$logy=0;
$logy=$HTTP_GET_VARS["logy"];
$str1=$HTTP_GET_VARS["str1"];
$str2=$HTTP_GET_VARS["str2"];
$str3=$HTTP_GET_VARS["str3"];
$str4=$HTTP_GET_VARS["str4"];


//$logx=1;
//$logy=0;
$width=$zoom*250; // Plus 20, 10 to the left, 10 to the right
$heigth=$zoom*100; // Plus 20, 10 to the top and 10 to the bottom

$x1 = preg_split ("/,/", $x1);
$y1 = preg_split ("/,/", $y1);
$x2 = preg_split ("/,/", $x2);
$y2 = preg_split ("/,/", $y2);
$x3 = preg_split ("/,/", $x3);
$y3 = preg_split ("/,/", $y3);
$x4 = preg_split ("/,/", $x4);
$y4 = preg_split ("/,/", $y4);


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
if (sizeof($x3) != sizeof($y3) ) {
	print "Error: sizeof(x) isnt the same as sizeof(y) !<br>\n";
	print "sizeof(x3) = " . sizeof($x3) . "<br>\n";
	print "sizeof(y3) = " . sizeof($y3) . "<br>\n";
	exit;
}
if (sizeof($x4) != sizeof($y4) ) {
	print "Error: sizeof(x) isnt the same as sizeof(y) !<br>\n";
	print "sizeof(x4) = " . sizeof($x4) . "<br>\n";
	print "sizeof(y4) = " . sizeof($y4) . "<br>\n";
	exit;
}

$datax1=array();
$datay1=array();
$datax2=array();
$datay2=array();
$datax3=array();
$datay3=array();
$datax4=array();
$datay4=array();

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

$sumofY3=0.0;
for($k=0;$k<sizeof($x3);$k++){
	if($x3[$k]>$maxX) 
	  $maxX=$x3[$k];
	if($x3[$k]<$minX) 
	  $minX=$x3[$k];
	array_push($datax3,$x3[$k]);
	$sumofY3=$sumofY3+$y3[$k];
//	array_push($datay3,$sumofY3);
	array_push($datay3,$y3[$k]);				
//	print "[$k] x3= " . $x3[$k] . " y3 = " . $sumofY3 . "<br>\n"; 
}


$sumofY4=0.0;
for($k=0;$k<sizeof($x4);$k++){
	if($x4[$k]>$maxX) 
	  $maxX=$x4[$k];
	if($x3[$k]<$minX) 
	  $minX=$x4[$k];
	array_push($datax4,$x4[$k]);
	$sumofY4=$sumofY4+$y4[$k];
//	array_push($datay4,$sumofY4);
	array_push($datay4,$y4[$k]);				
//	print "[$k] x4= " . $x4[$k] . " y4 = " . $sumofY4 . "<br>\n"; 
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

$graph->SetScale($scale, 0, 1, -1.1*$maxX, 1.1*$maxX);
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


$sp3= new ScatterPlot($datay3,$datax3);
//$sp3->SetLinkPoints(true,"blue",2);
$sp3->mark->SetType(MARK_STAR);
$sp3->SetImpuls();
//$sp3->mark->SetFillColor("navy");
//$sp3->mark->SetWidth(3);
$sp3->value->Show();
$sp3->SetLegend(sprintf("%s", $str3));
$graph->Add($sp3);


$sp4= new ScatterPlot($datay4,$datax4);
//$sp4->SetLinkPoints(true,"blue",2);
$sp4->mark->SetType(MARK_DIAMOND);
$sp4->SetImpuls();
//$sp4->mark->SetFillColor("navy");
//$sp4->mark->SetWidth(3);
$sp4->value->Show();
$sp4->SetLegend(sprintf("%s", $str4));
$graph->Add($sp4);



$graph->Stroke();


?>


