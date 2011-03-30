<?
require("sessionCheck.php");
require("config.inc");

include ("jpgraph.php");
include ("jpgraph_log.php");
include ("jpgraph_scatter.php");
include ("jpgraph_line.php");


$colors	= array("1" =>  "blue",
		"2" =>  "green",
		"3" =>  "cyan",
		"4" =>  "magenta",
		"5" =>  "yellow",
		"6" =>  "black",
		"7"=>   "deepskyblue",
		"8"=>   "forestgreen",
		"9"=>   "khaki",
		"10"=>  "gold",
		"11"=>  "lawngreen",
		"12"=>  "dimgray",
		"13"=>  "lightblue",
		"14"=>  "lightblue1",
		"15"=>  "lightblue2",
		"16"=>  "lightblue3",
		"17"=>  "lightblue4",
		"18"=>  "indianred",
		"19"=>  "indianred1",
		"20"=>  "gray5",
		"21"=>  "gray6",
		"22"=>  "gray7",
		"23"=>  "gray8",
		"24"=>  "pink"); 

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
$MAMPid=$_GET["MAMPid"];

$LENHRS=$LEN*60;

$Connect = mysql_connect($DB_SERVER, $user, $password) or die ("Cant connect to MySQL at $DB_SERVER");
mysql_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");

$sql2="SELECT * FROM " . $MAMPid ."_Linkutilization ORDER BY id DESC LIMIT " . $LENHRS;
//int "sql2 = $sql2 <br>\n";

$result2=mysql_query($sql2);
if(!$result2){
	print " MySQL pr. " . mysql_error() ."</td>";
	exit;
} 
$row2=mysql_fetch_array($result2);


$k=1;
$j=1;
while($row2=mysql_fetch_array($result2)){
	if(($k)%60==0){
		$leX = preg_split("/,/",$row2["bins"]);
		$leY = preg_split("/,/",$row2["counters"]);
		$datax[$j]= $leX;
		$datay[$j]= $leY;
//		print "Hr $j = " . $datay[$k] ." <br>\n";
		$j++;
	}
	$k++;
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
//	print "This data. $j / $LEN <br>\n";
//	print "datay=<br>\n";
//	print_r($datay[$j]);
//	print "<br>\ndatax=<br>\n";
//	print_r($datax[$j]);
//	print "<br>\n";
	$sp[$j]= new ScatterPlot($datay[$j],$datax[$j]);
//	$sp[$j]= new LinePlot($datay[$j]);
	
	$theColor=sprintf("#%0XFFFF",$Color);
//	print "Color = $theColor <-> " . $colors[$j] . "<br>\n";
	$Color-=$colorStep;
	$sp[$j]->SetLinkPoints(true,$colors[$j],2);
//	$sp[$j]->SetColor($colors[$j]);//$theColor);
//	$sp[$j]->mark->SetType(MARK_FILLEDCIRCLE);
	$sp[$j]->SetWeight(2);
	$sp[$j]->SetLegend(sprintf("-%d",$LEN-$j));
	$graph->Add($sp[$j]);
}

$graph->Stroke();
//$grap->legend

?>


