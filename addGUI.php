<?
require("sessionCheck.php");
require("config.inc");

?>
<html>
<? 
print $pageStyle;
?>



<?

$Connect = mysql_connect($DB_SERVER, $user, $password) or die ("Cant connect to MySQL at $DB_SERVER");
mysql_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");

$toggle=0;

$sql_query="SELECT * FROM guiconfig WHERE selected=1";
$result=mysql_query ($sql_query);
if(!$result) {
	print "sq: $sql_q <br>\n";
	print "Mysql Problems: " . mysql_error() . "<br>\n";
}

if(mysql_num_rows($result)>0) {
?>	
	<form action="addGUI2.php?SID=<? print $sid;?>&ID=<? print $_GET["ID"]; ?>" method="POST" target="view">

<?
	$row = mysql_fetch_array($result);
	print "<table border=0>";

	print "<tr><th><b>pageStyle</b></th></tr>";
	print "<tr><td><textarea name=good rows=20 cols=80>". $row["pageStyle"] ."</textarea></td></tr>";

	print "<tr><th><b>pageStyleBad</b></th></tr>";
	print "<tr><td><textarea name=bad rows=20 cols=80>". $row["pageStyleBad"] . "</textarea></td></tr>";

	print "<tr><th><b>projectName</b></th></tr>";
	print "<tr><td><input type=text name=name size=60 maxlength=80 value='". $row["projectName"] . "'></td></tr>";

	print "</table>";
?>
	<tr><td><input type="submit" value="Add"></td><td><input type="reset" value="Reset"></td></td>
<?
} else {
	print "No entries in the access table, How the hell did you get here!?!?!? ";
	exit;
}


?>
</body></html>