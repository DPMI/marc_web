<?
require("sessionCheck.php");
require("config.inc");

$sid=$_GET["SID"];
$nSid=session_id();
if($sid!=$nSid) {
	print "The passed SID is not equal to the one found here. Forgot to login?";
//	print "$sid == $nSid <br>\n";
		exit();
} 
/*
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
*/

?>
<html>
<? 
print $pageStyle;

$MAMPid=$_GET["MAMPid"];

$Connect = mysql_connect($DB_SERVER, $user, $password) or die ("Cant connect to MySQL at $DB_SERVER");
mysql_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");
if (isset($_SESSION["accesslevel"])) {
	$level=$_SESSION["accesslevel"];
} else {
	$level=0;
}
print "Clearing old table.<br>\n";
$tabel=$MAMPid."_filterlistverify";
$drop="TRUNCATE TABLE $tabel";
$result=mysql_query ($drop);
if(!$result) {
	print "sq: $drop <br>\n";
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
$type=7;
$message="JUNK";

$message=sprintf("%s%s",pack("N",$type),$message);

print "Contacting ctrl $IP:$port sending $message<br>\n";
$fp = fsockopen("udp://$IP", $port, $errno, $errstr);
if (!$fp) {
   echo "ERROR: $errno - $errstr<br />\n";
} else {
   fwrite($fp, $message);
   fclose($fp);
}
print "Sent message, pausing for 10 seconds to give MP time to respond.<br>\n";
sleep(10);
$toggle=0;
$tabel=$MAMPid."_filterlistverify";
$query2="SELECT * FROM $tabel";
$result2=mysql_query($query2);
print "<table border=1>\n";
if(!$result2) {
	print "<tr><td colspan=14> sq: $query2 Mysql Problems: " . mysql_error() . "</td></tr>\n";
} else {
	if(mysql_num_rows($result2)==0) {
	  print "<tr><td colspan=15>No Filters</td></tr>\n";
	} else {
	  print "<tr bgcolor=dddddd><th >Index</th><th>Filter_ID</th><th>CI</th><th>VLAN_TCI/<br>MASK</th>";
	  print "<th>ETH_TYPE/<br>MASK<th>ETH_SRC/<br>MASK</th><th>ETH_DST/<br>MASK</th>";
	  print "<th>IP_PROTO</th>";
	  print "<th>IP_SRC/<br>MASK</th><th>IP_DST/<br>MASK</th><th>SRC_PORT/<br>MASK</th><th>DST_PORT/<br>MASK</th>";
	  print "<th>DESTADDR/TYPE</th><th>CAPLEN</th><th></th></tr>\n";
	  while($row2 = mysql_fetch_array($result2)){
	    if($toggle==0) {
		$color="aaaaaa";
		$toggle=1;
	    } else {
		$color="bbbbbb";
		$toggle=0;
	    }	
 	    print "<tr bgcolor='$color' >";
	    print "<td>".$row2["ind"] . "</td>";
	    print "<td>".$row2["filter_id"] . "</td>";
  	    print "<td>".$row2["CI_ID"] . "</td>";
	    print "<td>".$row2["VLAN_TCI"] ."/<br>" .$row2["VLAN_TCI_MASK"]. "</td>";
	    print "<td>".$row2["ETH_TYPE"] ."/<br>" .$row2["ETH_TYPE_MASK"]. "</td>";
	    print "<td>".$row2["ETH_SRC"] ."/<br>" .$row2["ETH_SRC_MASK"]. "</td>";
	    print "<td>".$row2["ETH_DST"] ."/<br>" .$row2["ETH_DST_MASK"]. "</td>";
	    print "<td>".$row2["IP_PROTO"] . "</td>";
	    print "<td>".$row2["IP_SRC"] ."/<br>" .$row2["IP_SRC_MASK"]. "</td>";
	    print "<td>".$row2["IP_DST"] ."/<br>" .$row2["IP_DST_MASK"]. "</td>";
  	    print "<td>".$row2["SRC_PORT"] ."/<br>" .$row2["SRC_PORT_MASK"]. "</td>";
	    print "<td>".$row2["DST_PORT"] ."/<br>" .$row2["DST_PORT_MASK"]. "</td>";
	    print "<td>".$row2["DESTADDR"] ."/" .$row2["TYPE"] . "</td>";
	    print "<td>".$row2["CAPLEN"] . "</td>";
	    print "<td><a href='editFilter.php?SID=$sid&filter_id=".$row2["filter_id"] ."&MAMPid=".$row["MAMPid"] ."'><img width=12 height=13  border=0 ALT='Edit' src='button_edit.png'></a>";
	    print "<a href='delFilter.php?SID=$sid&filter_id=".$row2["filter_id"] ."&MAMPid=".$row["MAMPid"] ."'><img width=12 height=13  border=0 ALT='Drop' src='button_drop.png'></a>";
	    print "<a href='verifyFilter.php?SID=$sid&filter_id=".$row2["filter_id"] ."&MAMPid=".$row["MAMPid"] ."'><img width=12 height=13  border=0 ALT='Verify' src='button_properties.png'></a>";
	    print "</td>";
	    print "<tr>\n";
	  }
        }
}
print "</table>\n";
?>

<form action="verifyFilters2.php?SID=<? print $sid;?>&ID=<? print $ID; ?>" method="POST" name=myForm target="view">
<table border=1 width=50%>
<tr><td colspan=4>By clicking Replace below, you will replace any and all filters that are not found on the particular MP. This is good when you are only missing a few rules, it is BAD when you are missing a LOT of rules. It is up to you. NOT IMPLEMENTED!<td></tr>
<input type=hidden name=MAMPid value=<? print $row["MAMPid"]; ?>> 
<tr><td colspan=2><div align=center><input type="submit" value="Replace"></div></td><td colspan=2><div align=center><input type="reset" value="Reset"></div></td></tr>
</table>
</form>

</body>
</html>
