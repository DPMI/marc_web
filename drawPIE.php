<?php
include ("jpgraph.php");
include ("jpgraph_pie.php");
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

$linkArray=array("0x0800" => "IPv4",
		"0x0806" => "ARP",
		"0x86DD" => "IPv6",
		"0x809B" => "AppleTalk");

$icmpArray=array("0" => "Echo Reply",
		"3" =>  "Destination Unreachable",
		"4" =>  "Source Quench",
		"5" =>  "Redirect",
		"8" =>  "Echo Request",
		"9" =>  "Router Advertisement",
		"10"=>  "Router Solicitation",
		"11"=>  "Time Exceeded",
		"12"=>  "Parameter Problem",
		"13"=>  "Timestamp request",
		"14"=>  "Timestamp reply",
		"15"=>  "Info. request(Ob)",
		"16"=>  "Info. reply(ob)",
		"17"=>  "Address mask request",
		"18"=>  "Address mask reply");


$width=$zoom*150; // Plus 20, 10 to the left, 10 to the right
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
$remainders=0;

	//print "data= [<br>\n";
	$sumofY=0.0;
	for($k=1;$k<sizeof($x);$k++){
		if($y[$k]>100) {
			array_push($datay,$y[$k]);
		//	print "x[k] = " . $x[$k] . "<br>\n";
			if(strcmp($title,"icmp") == 0){
				$value=$x[$k];
				array_push($datax,sprintf("0x%0x - %s",($value-1),$icmpArray[$value-1]));
			} else {
				if(strcmp($title,"network")==0){
					$str=sprintf("0x%04x",$x[$k]);
					$string=$linkArray["$str"];
					if($string){
						array_push($datax,sprintf("0x%0x - %s",$x[$k],$string));
					} else {
						array_push($datax,sprintf("0x%0x - Unknown",$x[$k]));
					}
				} elseif(strcmp($title,"transport")==0){
					array_push($datax,sprintf("0x%0x - %s",$x[$k],getprotobynumber($x[$k])));
				} elseif(strcmp($title,"application")==0){
					array_push($datax,sprintf("%0d - %s",$x[$k],getservbyport($x[$k],'tcp')));
				} elseif(strcmp($title,"vlan")==0){
					if($x[$k]==5000) {
						array_push($datax,sprintf("No VLAN"));
					} else {
						array_push($datax,sprintf("0x%0d ",$x[$k]));
					}
				} else  {
					array_push($datax,sprintf("0x%0x", $x[$k]));
				}
			}
		} else {
			$remainders+=$y[$k];
		}
		//	print "x= " . $x[$k] . "<br>\n";
	}
	//print_r($datax);
	array_push($datay,$remainders);
	array_push($datax,"Others");
	$graph=new PieGraph($width, $heigth, "auto");
	$graph->SetShadow();

	$graph->title->Set("Protocol Distribution - $title");
	$graph->title->SetFont(FF_FONT1,FS_BOLD);

	$sp1= new PiePlot($datay);
	$sp1->SetTheme("earth");
	$sp1->SetLegends($datax);
	$graph->Add($sp1);
	$graph->Stroke();

?>


