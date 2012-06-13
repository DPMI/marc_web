<?
require("sessionCheck.php");
require("config.php");

?>
<html>
<?
print $pageStyle;
?>
<?

if ($accesslevel > 1 ) {
	// To low access.
	print "<h1>UN-AUTHORIZED!</H1>";
	print "Event has been logged, and sysadmin contacted.";
	exit;
}
$ID=$_GET["ID"];

?>


<form action="addPage2.php?SID=<? print $sid;?>&ID=<? print $ID; ?>" method="POST" target="view">
<table BORDER >

<tr><td bgcolor = D3DCE3><div align=right>URL</div></td><td><input name="url" type="text" size="60" maxlength="200"></td></tr>
<tr><td bgcolor = D3DCE3><div align=right>Access Level</div></td><td>
<select name = "accesslevel" >
	<option value="0">Public</option>
	<option value="1">Members</option>
	<option value="2">Admin</option>
</select>
</td></tr>

<tr><td bgcolor = D3DCE3><div align=right>Text</div></td><td>
<textarea name="text" rows="7" cols="40"><? print $row["text"]; ?></textarea></td></tr>
<tr><td bgcolor = D3DCE3><div align=right><input type="submit" value="Update Page"></div></td><td><input type="reset" value="Reset"></td></td>
</table>
</form>

</html>