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
$ID=$_GET["id"];
if (isset($_SESSION["accesslevel"])) {
	$level=$_SESSION["accesslevel"];
} else {
	$level=0;
}
$sql_query="SELECT * FROM measurementpoints where id=$ID";
$result=mysql_query ($sql_query);
if(!$result) {
	print "sq: $sql_q <br>\n";
	print "Mysql Problems: " . mysql_error() . "<br>\n";
}
$row = mysql_fetch_array($result);

$IP=$row["ip"];
$port=$row["port"];
?>
<table BORDER>
<tr><th>Id</th><th>Type</th><th>Message</th></tr>
<tr><td>1</td><td>Authorize MP.</td><td>MPID</td></tr>
<tr><td>2</td><td>Reload filters</td><td>void</td></tr>
<tr><td>3</td><td>Get a new filter.</td><td>Filter Id </td></tr>
<tr><td>4</td><td>Change filter.</td><td>Filter Id</td></tr>
<tr><td>5</td><td>Drop filter.</td><td>Filter Id</td></tr>
<tr><td>6</td><td>Verify Filter.</td><td>Filter Id</td></tr>
<tr><td>7</td><td>Verify All Filter</td><td>void</td></tr>
<tr><td>8</td><td>Terminate MP</td><td>Magic word</td></tr>
<tr><td>9</td><td>Flush Consumer Buffers</td><td>void</td></tr>
<tr><td>10</td><td>Flush Consumer X Buffer</td><td>Consumer ID</td></tr>
</table>

<form action="sndMsg3.php?SID=<? print $sid;?>&ID=<? print $ID; ?>" method="POST" target="view">
<table BORDER >
<tr><td bgcolor = D3DCE3><div align=right>type</div></td><td><input name="type" type="text" size="2" maxlength="2" ></td></tr>
<tr><td bgcolor = D3DCE3><div align=right>message</div></td><td><input name="message" type="text" size="100" maxlength="100" ></td></tr>
<tr><td><input type="submit" value="Send"></td><td><input type="reset" value="Reset"></td></td>
<input type=hidden name=ip value=<? print $IP; ?>>
<input type=hidden name=port value=<? print $port; ?>>
</table>
</form>
</body></html>