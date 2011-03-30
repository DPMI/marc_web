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

function ntohl($port) {
  $b=pack("N", $port);
  return $b;
}



print "Lets talk tell the client.<br>\n";
$IP=$_POST["ip"];
$port=$_POST["port"];

$type=$_POST["type"];
$message=$_POST["message"];

$message=ntohl($type) . sprintf("%s",$message);	


//$message=sprintf("%c%s",$type,$message);

print "Contacting ctrl $IP:$port <h1>$message</h1>\n";
$fp = fsockopen("udp://$IP", $port, $errno, $errstr);
if (!$fp) {
   echo "ERROR: $errno - $errstr<br />\n";
} else {
   fwrite($fp, $message);
   fclose($fp);
}





?>
</body></html>