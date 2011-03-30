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


$ID=		$HTTP_GET_VARS["ID"];
$string=	$HTTP_POST_VARS["string"];
$url=		$HTTP_POST_VARS["url"];
$accesslevel=	$HTTP_POST_VARS["accesslevel"];
$comment=	$HTTP_POST_VARS["comment"];
$type=		$HTTP_POST_VARS["type"];



$Connect = mysql_connect($DB_SERVER, $user, $password) or die ("Cant connect to MySQL at $DB_SERVER");
mysql_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");
	
$sql_update="UPDATE mainmenu SET string='$string', url='$url', accesslevel='$accesslevel', comment='$comment', type='$type' WHERE id='$ID'";
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
?>
Update complete

</html>