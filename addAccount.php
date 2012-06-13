<?
require("sessionCheck.php");
require("config.php");
?>
<html>
<head>
  <title>Add account</title>

  <meta http-equiv="content-type"
 content="text/html; charset=ISO-8859-1">
</head>
<?
print $pageStyle;
?>

<form action="addAccount2.php?SID=<? print $sid;?>&ID=<? print $ID; ?>" method="POST" target="view">
<table BORDER >

<tr><td bgcolor = D3DCE3><div align=right>UserName</div></td><td><input name="uname" type="text" size="14" maxlength="14" value="<? print $row["uname"]; ?>"></td></tr>
<tr><td bgcolor = D3DCE3><div align=right>Password</div></td><td><input name="passwd" type="password" size="14" maxlength="14" value="<? print $row["passwd"]; ?>"></td></tr>
<tr><td bgcolor = D3DCE3><div align=right>Status</div></td><td>
<select name = "status" >
	<option value="0">Public</option>
	<option value="1">Members</option>
	<option value="2">Admin</option>
</select>
</td></tr>
<tr><td bgcolor = D3DCE3><div align=right>Comment</div></td><td><input name="comment" type="text" size="14" maxlength="14" value="<? print $row["comment"]; ?>"></td></tr>
<tr><td bgcolor = D3DCE3><div align=right>Name</div></td><td><input name="name" type="text" size="14" maxlength="14" value="<? print $row["comment"]; ?>"></td></tr>
<tr><td bgcolor = D3DCE3><div align=right>Email</div></td><td><input name="email" type="text" size="14" maxlength="14" value="<? print $row["comment"]; ?>"></td></tr>
<tr><td><input type="submit" value="Add User"></td><td><input type="reset" value="Reset"></td></td>

</table>
</form>
</body>
</html>
