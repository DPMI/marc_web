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


?>
<html>
<? 
print $pageStyle;
?>



<?

$Connect = mysql_connect($DB_SERVER, $user, $password) or die ("Cant connect to MySQL at $DB_SERVER");
mysql_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");

$toggle=0;

$sql_query="SELECT * FROM guiconfig WHERE id=" . $HTTP_GET_VARS["ID"];
$result=mysql_query ($sql_query);
if(!$result) {
	print "sq: $sql_q <br>\n";
	print "Mysql Problems: " . mysql_error() . "<br>\n";
}

if(mysql_num_rows($result)>0) {
?>	
	<form action="updateGUI.php?SID=<? print $sid;?>&ID=<? print $HTTP_GET_VARS["ID"]; ?>" method="POST" target="view">

<?
	$row = mysql_fetch_array($result);
	print "<table border=0>";

	print "<tr><th><b>pageStyle</b></th></tr>";
	print "<tr><td><textarea name=good rows=20 cols=80>". $row["pageStyle"] ."</textarea></td></tr>";

	print "<tr><th><b>pageStyleBad</b></th></tr>";
	print "<tr><td><textarea name=bad rows=20 cols=80>". $row["pageStyleBad"] . "</textarea></td></tr>";

	print "<tr><th><b>projectName</b></th></tr>";
	print "<tr><td><input type=text name=projName size=60 maxlength=80 value='". $row["projectName"] . "'></td></tr>";

	print "</table>";
?>
	<tr><td><input type="submit" value="Update config"></td><td><input type="reset" value="Reset"></td></td>
	<input type="hidden" name=id value=<? print $row["id"];?>>
<?
} else {
	print "No entries in the access table, How the hell did you get here!?!?!? ";
	exit;
}


?>
</body></html>