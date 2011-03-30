<?
require("sessionCheck.php");
require("config.inc");

$sid=$_GET["SID"];
$nSid=session_id();
if($sid!=$nSid) {
	print "The passes SID is not equal to the one found here.. problems!";
//	print "$sid == $nSid <br>\n";
		exit();
}


$ID=		$HTTP_POST_VARS["id"];

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


$sql_select="SELECT * FROM access where passwd='$passwd'";
$result=mysql_query($sql_select);
if(!$result) {
	print "MySQL error: " . mysql_error();
	exit;
}
$n=mysql_num_rows($result);
//print "sql: $sql_select <br>\n";
//print "n  : $n <br>\n";
if ($n==0) { 
	// New password 
	print "Changing passwd..<br>\n";
	$sql_update="UPDATE access SET uname='$uname', passwd=PASSWORD('$passwd'), status='$status', comment='$comment', Name='$name', Email='$email', time=NOW() WHERE id='$ID'";
} else {
// No change to password.	
	$sql_update="UPDATE access SET uname='$uname', status='$status', comment='$comment', Name='$name', Email='$email', time=NOW() WHERE id='$ID'";
	print "Not changing passwd..<br>\n";
}	
$result=mysql_query($sql_update);
if(!$result) {
	print "MySQL error: " . mysql_error();
	exit;
}
	
//header("Location: root.php?SID=$sidVAR");

?>
<html>
<? 
print $pageStyle;
//print "sql: $sql_update <br>\n";
?>

Update complete

</html>