<?
require("config.inc");

session_start();
// Use $HTTP_SESSION_VARS with PHP 4.0.6 or less
if (session_is_registered("OK") and isset($HTTP_SESSION_VARS["SID"])) {
//    print "OK";
	$sid=$HTTP_SESSION_VARS["SID"];
//	print "SID = $sid \n";
	header("Location: framesMgnt.php?SID=$sid");
	exit;
}

?>
<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
   <meta name="GENERATOR" content="Mozilla/4.78 [en] (Windows NT 5.0; U) [Netscape]">
</head>
<? 
print $pageStyle;
?>


<h1><u>Login</u></h1>
This site uses cookies and sessions.<br>
<center>
<form action="loginVerification.php" method=post>
<table border=1>
<tr><td>User Name</td><td><input type=text name=uName></td></tr>
<tr><td>Password</td><td><input type=password name=pWord></td></tr>
<tr><td colspan=2 align=center><input type=submit value="Enter"></tr>
</table>
</form></center>

</body>
</html>
