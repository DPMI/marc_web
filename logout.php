<?
require("sessionCheck.php");
require("config.inc");

$sid=$_GET["SID"];
$nSid=session_id();
if($sid!=$nSid) {
	print "The passes SID is not equal to the one found here.. problems!";
	print "$sid == $nSid <br>\n";
		exit();
}

session_unset();
//print "<h3>Logged out</h3>";
header("Location: index.html");
?>
