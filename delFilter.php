<?
require("sessionCheck.php");
require("config.php");
?>
<html>
<?
print $pageStyle;

$FILTER_ID=$_GET["filter_id"];
$MAMPid=$_GET["MAMPid"];

$Connect = mysqli_connect($DB_SERVER, $user, $password) or die ("Cant connect to MySQL at $DB_SERVER");
mysqli_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");
if (isset($_SESSION["accesslevel"])) {
	$level=$_SESSION["accesslevel"];
} else {
	$level=0;
}
$tabel=$MAMPid."_filterlist";
$query2="DELETE FROM $tabel WHERE filter_id='$FILTER_ID'";
$result2=mysqli_query($query2);
if(!$result2) {
	print "sq: $query2 Mysql Problems: " . mysqli_error() . "\n";
	return;
}

$query="SELECT * FROM measurementpoints WHERE MAMPid='$MAMPid'";
$result=mysqli_query ($query);
if(!$result) {
	print "sq: $query <br>\n";
	print "Mysql Problems: " . mysqli_error() . "<br>\n";
	return;
}
$row = mysqli_fetch_array($result);

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
