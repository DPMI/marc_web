<?
require("sessionCheck.php");
require("config.inc");

$sid=$_GET["SID"];
//session_start();
$nSid=session_id();
if($sid!=$nSid) {
	print "The passes SID is not equal to the one found here.. problems!";
//	print "$sid == $nSid <br>\n";
		exit();
}

?>
<html>
<? 
print $pageStyle;

$FILTER_ID=$_GET["filter_id"];
$MAMPid=$_GET["MAMPid"];

$Connect = mysql_connect($DB_SERVER, $user, $password) or die ("Cant connect to MySQL at $DB_SERVER");
mysql_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");
if (isset($_SESSION["accesslevel"])) {
	$level=$_SESSION["accesslevel"];
} else {
	$level=0;
}
$tabel=$MAMPid."_filterlist";
$query2="DELETE FROM $tabel WHERE filter_id='$FILTER_ID'";
$result2=mysql_query($query2);
if(!$result2) {
	print "sq: $query2 Mysql Problems: " . mysql_error() . "\n";
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
$type=5;
$message=$FILTER_ID;

$message=sprintf("%s%s",pack("N",$type),$message);

print "Contacting ctrl $IP:$port <h1>$message</h1>\n";
$fp = fsockopen("udp://$IP", $port, $errno, $errstr);
if (!$fp) {
   echo "ERROR: $errno - $errstr<br />\n";
} else {
   fwrite($fp, $message);
   fclose($fp);
}

?>

</body>
</html>
