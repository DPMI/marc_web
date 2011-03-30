<?
require("sessionCheck.php");
require("config.inc");


$sid=$_GET["SID"];
if (isset($sid)) {
//	session_start();
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
?>



<?
require("config.inc");

$Connect = mysql_connect($DB_SERVER, $user, $password) or die ("Cant connect to MySQL at $DB_SERVER");
mysql_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");

$toggle=0;

$order=$_GET["order"];
if (isset($_SESSION["accesslevel"])) {
	$level=$_SESSION["accesslevel"];
} else {
	$level=0;
}

$sql_query="SELECT * FROM measurementpoints";
if($order!=""){
	$sql_query=$sql_query . " ORDER BY $order";
}	

$result=mysql_query ($sql_query);
if(!$result) {
	print "sq: $sql_q <br>\n";
	print "Mysql Problems: " . mysql_error() . "<br>\n";
}

if(mysql_num_rows($result)>0) {
	print "<table border=0>";
	print "<th><b><a href=\"listMPs.php?SID=$sid&order=id\">ID</b></th>";
	print "<th><b><a href=\"listMPs.php?SID=$sid&order=name\">name</a></b></th>\n";
	print "<th><b><a href=\"listMPs.php?SID=$sid&order=ip\">ip</a></b></th>\n";
	print "<th><b><a href=\"listMPs.php?SID=$sid&order=port\">ip</a></b></th>\n";
	print "<th><b><a href=\"listMPs.php?SID=$sid&order=mac\">mac</b></th>";
	print "<th><b><a href=\"listMPs.php?SID=$sid&order=comment\">comment</a></b></th>\n";
	print "<th><b><a href=\"listMPs.php?SID=$sid&order=time\">time</b></th>";
	print "<th><b><a href=\"listMPs.php?SID=$sid&order=MAMPid\">MAMPid</a></b></th>\n";
	print "<th><b><a href=\"listMPs.php?SID=$sid&order=maxFilters\">maxFilters</a></b></th>\n";
	print "<th><b>Send to MP</a></b></th>\n";

	while($row = mysql_fetch_array($result)) {
		$noEthers=0;
		if($toggle==0) {
			$color="CCCCCC";
			$toggle=1;
		} else {
			$color="DDDDDD";
			$toggle=0;
		}	

		print 	"<tr bgcolor='$color' >
		<td>". $row["id"] . "</a></td>
		<td>". $row["ip"] . "</a></td>
		<td>". $row["port"] . "</a></td>
		<td>". $row["name"] ."</td>
		<td>". $row["mac"] ."</td>
		<td>". $row["comment"] . "</td>
		<td>". $row["time"] ."</td>
		<td>". $row["MAMPid"] . "</td>
		<td>". $row["maxFilters"] ."</td>";
		print "<td><a href=\"sndMsg2.php?SID=$sid&id=". $row["id"] ."\">SendTo</a></td></tr>\n";
	}
	print "</table>";
} else {
	print "No files yet.";
	exit;
}


?>
</body></html>