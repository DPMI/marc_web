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


$ID=		$_GET["ID"];


if($status < $accesslevel) {
	print "<h1>ERROR: You cant assign a user a better access level than you have.</h1>";
	print "Logged and noted.";
	exit;
}


$Connect = mysql_connect($DB_SERVER, $user, $password) or die ("Cant connect to MySQL at $DB_SERVER");
mysql_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");


	
$sql_update="UPDATE guiconfig SET selected='0' WHERE id='$selectedID'";
$result=mysql_query($sql_update);
if(!$result) {
	print "MySQL error: " . mysql_error();
	exit;
}

$sql_update="UPDATE guiconfig SET selected='1' WHERE id='$ID'";
$result=mysql_query($sql_update);
if(!$result) {
	print "MySQL error: " . mysql_error();
	exit;
}

	
//header("Location: root.php?SID=$sidVAR");

$sql_update="SELECT * FROM guiconfig WHERE selected=1";
//print "sql_update : $sql_update <br>\n";
$result=mysql_query($sql_update);
if(!$result) {
	print "MySQL error: " . mysql_error();
	exit;
}


if(mysql_num_rows($result)>0) {
	$row = mysql_fetch_array($result);
} else { // PRoblems. Use some default

}

$selectedID=$row["id"];
$pageStyle=$row["pageStyle"];
$pageStyleBad=$row["pageStyleBad"];
$projectName=$row["projectName"];

?>
<html>
<? 
print $pageStyle;
//print "sql: $sql_update <br>\n";
?>

Update complete

</html>