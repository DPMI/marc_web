<?
require("sessionCheck.php");
require("config.inc");

$sid=$HTTP_GET_VARS["SID"];
session_start();
$nSid=session_id();
if($sid!=$nSid) {
	print "The passes SID is not equal to the one found here.. problems!";
//	print "$sid == $nSid <br>\n";
		exit();
}


$uname=		$HTTP_POST_VARS["uname"];
$passwd=	$HTTP_POST_VARS["passwd"];
$status=	$HTTP_POST_VARS["status"];
$comment=	$HTTP_POST_VARS["comment"];
$name=		$HTTP_POST_VARS["name"];
$email=		$HTTP_POST_VARS["email"];

if($status < $accesslevel) {
	print "<h1>ERROR: You cant assign a user a better access level than you have.</h1>";
	print "Logged and noted.";
	exit;
}


$Connect = mysql_connect($DB_SERVER, $user, $password) or die ("Cant connect to MySQL at $DB_SERVER");
mysql_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");

$sql_update="INSERT INTO access SET uname='$uname', passwd=PASSWORD('$passwd'), status='$status', comment='$comment', name='$name', email='$email'";
$result=mysql_query($sql_update);
if(!$result) {
	print "MySQL error: " . mysql_error();
	exit;
}
//header("Location: root.php?SID=$sidVAR");

?>
<? 
print $pageStyle;
?>


Add complete

</html>