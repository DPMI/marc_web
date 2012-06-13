<?
require("sessionCheck.php");
require("config.php");
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
$ID=$_GET["ID"];
$Connect = mysql_connect($DB_SERVER, $user, $password) or die ("Cant connect to MySQL at $DB_SERVER");
mysql_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");
$sql_query="SELECT * FROM pages where id=".$ID;
$result=mysql_query($sql_query) or die ("Error in mysql query: $sql_query \n" . mysql_error() ."<br>\n");
$row=mysql_fetch_array($result);
?>


<form action="updatePage.php?SID=<? print $sid;?>&ID=<? print $ID; ?>" method="POST" target="view">
<table BORDER >

<tr><td bgcolor = D3DCE3><div align=right>URL</div></td><td><input name="url" type="text" size="14" maxlength="14" value="<? print $row["url"]; ?>"></td></tr>
<tr><td bgcolor = D3DCE3><div align=right>Access Level</div></td><td>
<select name = "accesslevel" >
	<option value="0"<? if ($row["accesslevel"]==0) { print " selected ";} ?>>Public</option>
	<option value="1"<? if ($row["accesslevel"]==1) { print " selected ";} ?>>Members</option>
	<option value="2"<? if ($row["accesslevel"]==2) { print " selected ";} ?>>Admin</option>
</select>
</td></tr>


<tr><td bgcolor = D3DCE3><div align=right>Text</div></td><td>
<textarea name="text" rows="20" cols="60"><? print $row["text"]; ?></textarea></td></tr>
<tr><td><input type="submit" value="Update Page"></td><td><input type="reset" value="Reset"></td></td>
<input type="hidden" name=id value=<? print $ID;?>>
</table>
</form>
<p>You can use normal HTML.</p>
</html>