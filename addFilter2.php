<?
require("sessionCheck.php");
require("config.inc");
$sid=$_GET["SID"];
if (isset($sid)) {
	$nSid=session_id();
	if($sid!=$nSid) {
		print "The passes SID is not equal to the one found here.. problems!";
//		print "$sid == $nSid <br>\n";
			exit();
	}
} 
else {
	print "<html><head>\n";
	print "<title>404 Not Found</title>\n";
	print "</head><body>\n";
	print "<h1>Not Found</h1>\n";
	print "<p>The requested URL was not found on this server.</p>\n";
	print "<hr/>\n";
	print "<address>Apache/2.0.48 (Unix) DAV/2 PHP/4.3.4 Server at inga.its.bth.se Port 80</address>\n";
	print "</body></html>\n";
	exit();
}

?>
<html>
<? 
print $pageStyle;

$INDEX=$_POST["index"];
$FILTER_ID=$_POST["filter_id"];
$CI=$_POST["ci"]; 	
$VLAN_TCI=$_POST["vlan_tci"];
$VLAN_TCI_MASK=$_POST["vlan_tci_mask"]; 
$ETH_TYPE=$_POST["eth_type"]; 
$ETH_TYPE_MASK=$_POST["eth_type_mask"]; 
$ETH_SRC=$_POST["eth_src"];  
$ETH_SRC_MASK=$_POST["eth_src_mask"]; 
$ETH_DST=$_POST["eth_dst"];  
$ETH_DST_MASK=$_POST["eth_dst_mask"]; 
$IP_PROTO=$_POST["ip_proto"]; 
$IP_SRC=$_POST["ip_src"]; 	
$IP_SRC_MASK=$_POST["ip_src_mask"]; 
$IP_DST=$_POST["ip_dst"]; 	
$IP_DST_MASK=$_POST["ip_dst_mask"]; 
$SRC_PORT=$_POST["src_port"];  
$SRC_PORT_MASK=$_POST["src_port_mask"];
$DST_PORT=$_POST["dst_port"];  
$DST_PORT_MASK=$_POST["dst_port_mask"];
$DESTADDR=$_POST["destaddr"]; 
$TYPE=$_POST["stream_type"];
$CAPLEN=$_POST["caplen"];


$MAMPid=$_POST["mp"];
$filt="$MAMPid"."_filterlist";

$query="INSERT INTO $filt SET filter_id='$FILTER_ID', ind='$INDEX',
	CI_ID='$CI',VLAN_TCI='$VLAN_TCI',VLAN_TCI_MASK='$VLAN_TCI_MASK',
	ETH_TYPE='$ETH_TYPE',ETH_TYPE_MASK='$ETH_TYPE_MASK',
	ETH_SRC='$ETH_SRC',ETH_SRC_MASK='$ETH_SRC_MASK',
	ETH_DST='$ETH_DST',ETH_DST_MASK='$ETH_DST_MASK',
	IP_PROTO='$IP_PROTO',
	IP_SRC='$IP_SRC',IP_SRC_MASK='$IP_SRC_MASK',
	IP_DST='$IP_DST',IP_DST_MASK='$IP_DST_MASK',
	SRC_PORT='$SRC_PORT',SRC_PORT_MASK='$SRC_PORT_MASK',
	DST_PORT='$DST_PORT',DST_PORT_MASK='$DST_PORT_MASK',
	DESTADDR='$DESTADDR', TYPE='$TYPE', CAPLEN='$CAPLEN'";
$result=mysql_query ($query);
if(!$result) {
	print "sq: $query <br>\n";
	print "Mysql Problems: " . mysql_error() . "<br>\n";
	return;
}

$query="SELECT * FROM measurementpoints WHERE MAMPid='$MAMPid'";
$result=mysql_query ($query);
if(!$result) {
	print "sq: $query <br>\n";
	print "Mysql Problems: " . mysql_error() . "<br>\n";
	return;
}
$row = mysql_fetch_array($result);

$IP=$row["ip"];
$port=$row["port"];
$type=3;
$message=$FILTER_ID;

$message=sprintf("%s%s",pack("N",$type),$message);

print "Contacting ctrl $IP:$port <h1>$type -- $message</h1>\n";
$fp = fsockopen("udp://$IP", $port, $errno, $errstr);
if (!$fp) {
   echo "ERROR: $errno - $errstr<br />\n";
} else {
   fwrite($fp, $message);
   fclose($fp);
}





?>
</body></html>