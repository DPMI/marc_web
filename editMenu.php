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
//print "Accesslevel  = $accesslevel  || ". $_SESSION["accesslevel"] . "<br>\n";
//print "IP = $ip || " . $_SESSION["ip"] ."<br>\n";

if ($accesslevel > 1 ) { 
	// To low access.
	print "<h1>UN-AUTHORIZED!</H1>";
	print "Event has been logged, and sysadmin contacted.";
	exit;
}
$ID=$HTTP_GET_VARS["ID"];
$Connect = mysql_connect($DB_SERVER, $user, $password) or die ("Cant connect to MySQL at $DB_SERVER");
mysql_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");
$sql_query="SELECT * FROM mainmenu where id=".$ID;
$result=mysql_query($sql_query) or die ("Error in mysql query: $sql_query \n" . mysql_error() ."<br>\n");
$row=mysql_fetch_array($result);
?>


<form action="updateMenu.php?SID=<? print $sid;?>&ID=<? print $ID; ?>" method="POST" target="view">
<table BORDER >

<tr><td bgcolor = D3DCE3><div align=right>String</div></td><td><input name="string" type="text" size="60" maxlength="200" value="<? print $row["string"]; ?>"></td></tr>
<tr><td bgcolor = D3DCE3><div align=right>URL</div></td><td><input name="url" type="text" size="60" maxlength="200" value="<? print $row["url"]; ?>"></td></tr>
<tr><td bgcolor = D3DCE3><div align=right>Access Level</div></td><td>
<select name = "accesslevel" >
	<option value="0"<? if ($row["accesslevel"]==0) { print " selected ";} ?>>Public</option>
	<option value="1"<? if ($row["accesslevel"]==1) { print " selected ";} ?>>Members</option>
	<option value="2"<? if ($row["accesslevel"]==2) { print " selected ";} ?>>Admin</option>
</select>
</td></tr>

<tr><td bgcolor = D3DCE3><div align=right>Comment</div></td><td><input name="comment" type="text" size="60" maxlength="200" value="<? print $row["string"]; ?>"></td></tr>
<tr><td bgcolor = D3DCE3><div align=right>Type</div></td><td>
<select name = "type" >
	<option value="0"<? if ($row["type"]==0) { print " selected ";} ?>>Database</option>
	<option value="1"<? if ($row["type"]==1) { print " selected ";} ?>>File</option>
	<option value="2"<? if ($row["type"]==2) { print " selected ";} ?>>Link</option>
</select>

<tr><td><input type="submit" value="Update Page"></td><td><input type="reset" value="Reset"></td></td>
<input type="hidden" name=id value=<? print $ID;?>>
</table>
</form>

</html>