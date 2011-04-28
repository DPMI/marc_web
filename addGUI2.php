<?
require("sessionCheck.php");
require("config.inc");

$ID=		$HTTP_POST_VARS["id"];

$good=		$HTTP_POST_VARS["good"];
$bad=	$HTTP_POST_VARS["bad"];
$name=		$HTTP_POST_VARS["name"];

if($status < $accesslevel) {
	print "<h1>ERROR: You cant assign a user a better access level than you have.</h1>";
	print "Logged and noted.";
	exit;
}


$Connect = mysql_connect($DB_SERVER, $user, $password) or die ("Cant connect to MySQL at $DB_SERVER");
mysql_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");


	
$sql_update="INSERT INTO guiconfig SET pageStyle='$good', pageStyleBad='$bad', projectName='$name'";

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