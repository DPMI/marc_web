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
$ID=		$_GET["ID"];
$Connect = mysql_connect($DB_SERVER, $user, $password) or die ("Cant connect to MySQL at $DB_SERVER");
mysql_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");
$sql_query="SELECT * FROM access where id=".$ID;
$result=mysql_query($sql_query) or die ("Error in mysql query: $sql_query \n" . mysql_error() ."<br>\n");
$row=mysql_fetch_array($result);
?>


<form action="updateUser.php?SID=<? print $sid;?>&ID=<? print $ID; ?>" method="POST" target="view">
<table BORDER >

<tr><td bgcolor = D3DCE3><div align=right>UserName</div></td><td><input name="uname" type="text" size="20" maxlength="80" value="<? print $row["uname"]; ?>"></td></tr>
<tr><td bgcolor = D3DCE3><div align=right>Password</div></td><td><input name="passwd" type="password" size="20" maxlength="80" value="<? print $row["passwd"]; ?>"></td></tr>
<tr><td bgcolor = D3DCE3><div align=right>Status</div></td><td>
<select name = "status" >
	<option value="0" <? if ($row["status"]==0) { print "selected";}?> >Public</option>
	<option value="1" <? if ($row["status"]==1) { print "selected";}?>>Members</option>
	<option value="2" <? if ($row["status"]==2) { print "selected";}?>>Admin</option>
</select>
</td></tr>
<tr><td bgcolor = D3DCE3><div align=right>Comment</div></td><td><input name="comment" type="text" size="20" maxlength="80" value="<? print $row["comment"]; ?>"></td></tr>
<tr><td bgcolor = D3DCE3><div align=right>Name</div></td><td><input name="name" type="text" size="30" maxlength="80" value="<? print $row["Name"]; ?>"></td></tr>
<tr><td bgcolor = D3DCE3><div align=right>Email</div></td><td><input name="email" type="text" size="30" maxlength="80" value="<? print $row["Email"]; ?>"></td></tr>

<tr><td><input type="submit" value="Update User"></td><td><input type="reset" value="Reset"></td></td>
<input type="hidden" name=id value=<? print $ID;?>>
</table>
</form>

</html>