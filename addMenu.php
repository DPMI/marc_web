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


<form action="addMenu2.php?SID=<? print $sid;?>&ID=<? print $ID; ?>" method="POST" target="view">
<table BORDER >

<tr><td bgcolor = D3DCE3><div align=right>String</div></td><td><input name="string" type="text" size="60" maxlength="200"></td></tr>
<tr><td bgcolor = D3DCE3><div align=right>URL</div></td><td><input name="url" type="text" size="60" maxlength="200"></td></tr>
<tr><td bgcolor = D3DCE3><div align=right>Access Level</div></td><td>
<select name = "accesslevel" >
	<option value="0">Public</option>
	<option value="1">Members</option>
	<option value="2">Admin</option>
</select>
</td></tr>
<tr><td bgcolor = D3DCE3><div align=right>Comment</div></td><td><input name="comment" type="text" size="60" maxlength="200"></td></tr>
<tr><td bgcolor = D3DCE3><div align=right>Type</div></td><td>
<select name = "type" >
	<option value="0">Database</option>
	<option value="1">File</option>
	<option value="2">Link</option>
</select>
</td></tr>

<tr><td bgcolor = D3DCE3><div align=right><input type="submit" value="Update Page"></div></td><td><input type="reset" value="Reset"></td></td>
</table>
</form>

</html>