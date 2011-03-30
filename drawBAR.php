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

$x=$HTTP_GET_VARS["legend"]; // Comma separated. i.e. drawPDF.php?x=1,5.6,9&y=0.1,0.2,1
$y=$HTTP_GET_VARS["values"]; // Comma separated
$zoom=$HTTP_GET_VARS["zoom"];
$title=$HTTP_GET_VARS["title"];

$linkArray=array("0x0800" => "IPv4",
		"0x0806" => "ARP",
		"0x6000" => "DEC UA",
		"0x6001" => "DEC MPP DL",
		"0x6002" => "DEC MOP RC",
		"0x6003" => "DEC Router",
		"0x6004" => "DEC LAT",
		"0x6005" => "DEC DP",
		"0x6006" => "DEC CP",
		"0x6007" => "DEC LAVC",
		"0x6008" => "DEC UA",
		"0x6009" => "DEC UA",
		"0x6010" => "3Com",
		"0x8035" => "RARP",
		"0x8137" => "Novell",
		"0x8138" => "Novell",
		"0x86dd" => "IPv6", 
		"0x809B" => "AppleTalk",
		"0x880B" => "PPP",
		"0x8847" => "MPLS Uni",
		"0x8848" => "MPLS Multi",	
		"0x9000" => "Loopback",
		"0xffff" => "Reserved");

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


$width=$zoom*450; // Plus 20, 10 to the left, 10 to the right
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
	if(strcmp($title,"icmp") == 0){
		$value=$x[$k];
		array_push($datax,sprintf("0x%0x - %s",($value-1),$icmpArray[$value-1]));
	} else {	
		if(strcmp($title,"network")==0){
			$str=sprintf("0x%04x",$x[$k]);
			$string=$linkArray["$str"];
			if($string){
				array_push($datax,sprintf("0x%0x-%s",$x[$k],$string));
			} else {
				array_push($datax,sprintf("0x%0x",$x[$k]));	
			}
		} elseif(strcmp($title,"transport")==0){
			array_push($datax,sprintf("0x%0x - %s",$x[$k],getprotobynumber($x[$k])));
		} elseif(strcmp($title,"application")==0){
			if(strcmp($x[$k],"Others")==0){
				array_push($datax, sprintf("Other"));
			} else {
				array_push($datax,sprintf("%0d - %s",$x[$k],getservbyport($x[$k],'tcp')));
			}	
		} elseif(strcmp($title,"vlan")==0){
			if($x[$k]==5000) {
				array_push($datax,sprintf("No VLAN"));
			} else {	
				array_push($datax,sprintf("%d ",$x[$k]));	
			}
		} else  {
			array_push($datax,sprintf("0x%0x", $x[$k]));
		}
	}
	//	print "x= " . $x[$k] . "<br>\n";
}
//print_r($datax);

$graph=new Graph($width, $heigth, "auto");
$graph->img->SetMargin(40,50,20,120);
$graph->SetShadow();
$graph->SetScale("textlin");

//Set X-labels
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetLabelAngle(90);

$graph->title->Set("Protocol Distribution - $title");
$graph->title->SetFont(FF_FONT1,FS_BOLD);

$sp1= new BarPlot($datay);
$graph->Add($sp1);
$graph->Stroke();


?>


