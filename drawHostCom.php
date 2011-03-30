<?php
include ("jpgraph.php");
include ("jpgraph_canvas.php");
/*
drawPDF.php  -- Copyright Patrik Carlsson (patrik.carlsson@bth.se) 2002
Inputs. 
	x - a string with comma separated values. 
	y - a string with comma separated values.
	zoom - a zoom factor. (Only integers!!! 1,2,3,4)
example:
Output
	PNG image. 
example:


*/

$x=$HTTP_GET_VARS["x"]; // Comma separated. i.e. drawPDF.php?x=1,5.6,9&y=0.1,0.2,1
$y=$HTTP_GET_VARS["y"]; 
$zoom=$HTTP_GET_VARS["zoom"];
$logx=0;
$logx=$HTTP_GET_VARS["logx"];
$logy=0;
$logy=$HTTP_GET_VARS["logy"];

//$logx=1;
//$logy=0;
$width=$zoom*600; // Plus 20, 10 to the left, 10 to the right
$heigth=$zoom*250; // Plus 20, 10 to the top and 10 to the bottom

$x = preg_split ("/,/", $x);
$y = preg_split ("/,/", $y);


if (sizeof($x) != sizeof($y) ) {
	print "Error: sizeof(x) isnt the same as sizeof(y) !<br>\n";
	print "sizeof(x) = " . sizeof($x) . "<br>\n";
	print "sizeof(y) = " . sizeof($y) . "<br>\n";
	exit;
}
$dataSRC=array();
$dataDST=array();

//print "data= [<br>\n";
$sumofY=0.0;
$min=0;
$max=pow(2,32)-1;

//Find max src and dst.
for($k=0;$k<sizeof($x);$k++){
	if($x[$k]<$max) { $max=$x[$k];}
	if($x[$k]>$min){  $min=$x[$k];}
	if($y[$k]<$max) { $max=$y[$k];}
	if($y[$k]>$min){  $min=$y[$k];}

//	print "x= " . $x[$k] . " y = " . $y[$k] . "<br>\n";
}


for($k=0;$k<sizeof($x);$k++){
	array_push($dataSRC,10+($width-30)*$x[$k]/(pow(2,32)-1));
	array_push($dataDST,10+($width-30)*$y[$k]/(pow(2,32)-1));
//	print "x= " . $x[$k] . " y = " . $y[$k] . "<br>\n";
}

$graph=new CanvasGraph($width, $heigth, "auto");
$graph->SetMargin(5,11,6,11);
$graph->SetShadow();
$graph->SetMarginColor("teal");
$graph->InitFrame();

//ADD a SRC line
$graph->img->SetColor('black');
$graph->img->Line(10,30,$width-20,30);
//Add identifier
$txt1 = "Source";
$t1=new Text($txt1,$width/2-20,5);
$t1->Stroke($graph->img);

//Add a DST line
$graph->img->SetColor('black');
$graph->img->Line(10,$heigth-30,$width-20,$heigth-30);
//Add identifier
$txt2 = "Destination";
$t2=new Text($txt2,$width/2-20,$heigth-25);
$t2->Stroke($graph->img);


for($k=0;$k<sizeof($dataSRC);$k++){
// print "(".$dataSRC[$k] . ",30) - (" . $dataDST[$k] . "," . ($heigth-30) .")<br>\n";	
 $graph->img->Line($dataSRC[$k],30,$dataDST[$k],$heigth-30);	
} 


/* Draw markers source */
$graph->img->Line(10,25,10,35);
$t3=new Text("0.",10,15);
$t3->Stroke($graph->img);

$graph->img->Line(10+1*($width-30)/8,25, 10+1*($width-30)/8,35);
$t3=new Text("32.",10+1*($width-30)/8,15);
$t3->Stroke($graph->img);

$graph->img->Line(10+2*($width-30)/8,25, 10+2*($width-30)/8,35);
$t3=new Text("64.",10+2*($width-30)/8,15);
$t3->Stroke($graph->img);


$graph->img->Line(10+3*($width-30)/8,25, 10+3*($width-30)/8,35);
$t3=new Text("96.",10+3*($width-30)/8,15);
$t3->Stroke($graph->img);

$graph->img->Line(10+4*($width-30)/8,25, 10+4*($width-30)/8,35);
$t3=new Text("128.",10+4*($width-30)/8,15);
$t3->Stroke($graph->img);


$graph->img->Line(10+5*($width-30)/8,25, 10+5*($width-30)/8,35);
$t3=new Text("160.",10+5*($width-30)/8,15);
$t3->Stroke($graph->img);

$graph->img->Line(10+6*($width-30)/8,25, 10+6*($width-30)/8,35);
$t3=new Text("192.",10+6*($width-30)/8,15);
$t3->Stroke($graph->img);


$graph->img->Line(10+7*($width-30)/8,25, 10+7*($width-30)/8,35);
$t3=new Text("224.",10+7*($width-30)/8,15);
$t3->Stroke($graph->img);

$graph->img->Line($width-20,25, $width-20,35);
$t3=new Text("255.",$width-20,15);
$t3->Stroke($graph->img);


/* Draw markers destination */
$graph->img->Line(10,$heigth-35,10,$heigth-25);
$t3=new Text("0.",10,$heigth-25);
$t3->Stroke($graph->img);

$graph->img->Line(10+1*($width-30)/8,$heigth-25, 10+1*($width-30)/8,$heigth-35);
$t3=new Text("32.",10+1*($width-30)/8,$heigth-25);
$t3->Stroke($graph->img);

$graph->img->Line(10+2*($width-30)/8,$heigth-25, 10+2*($width-30)/8,$heigth-35);
$t3=new Text("64.",10+2*($width-30)/8,$heigth-25);
$t3->Stroke($graph->img);


$graph->img->Line(10+3*($width-30)/8,$heigth-25, 10+3*($width-30)/8,$heigth-35);
$t3=new Text("96.",10+3*($width-30)/8,$heigth-25);
$t3->Stroke($graph->img);

$graph->img->Line(10+4*($width-30)/8,$heigth-25, 10+4*($width-30)/8,$heigth-35);
$t3=new Text("128.",10+4*($width-30)/8,$heigth-25);
$t3->Stroke($graph->img);


$graph->img->Line(10+5*($width-30)/8,$heigth-25, 10+5*($width-30)/8,$heigth-35);
$t3=new Text("160.",10+5*($width-30)/8,$heigth-25);
$t3->Stroke($graph->img);

$graph->img->Line(10+6*($width-30)/8,$heigth-25, 10+6*($width-30)/8,$heigth-35);
$t3=new Text("192.",10+6*($width-30)/8,$heigth-25);
$t3->Stroke($graph->img);


$graph->img->Line(10+7*($width-30)/8,$heigth-25, 10+7*($width-30)/8,$heigth-35);
$t3=new Text("224.",10+7*($width-30)/8,$heigth-25);
$t3->Stroke($graph->img);

$graph->img->Line($width-20,$heigth-25, $width-20,$heigth-35);
$t3=new Text("255.",$width-20,$heigth-25);
$t3->Stroke($graph->img);



$graph->Stroke();


?>


