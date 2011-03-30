<?
require("sessionCheck.php");
require("config.inc");

$sid=$_GET["SID"];
session_start();
$nSid=session_id();
if($sid!=$nSid) {
	print "The passes SID is not equal to the one found here.. problems!";
	print "$sid == $nSid <br>\n";
		exit();
}


?>
<?
$id=$_GET["id"];

?>
<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
   <meta name="Author" content="Patrik Carlsson">
   <meta name="GENERATOR" content="Mozilla/4.75 [en] (Windows NT 5.0; U) [Netscape]">
   <title>Root</title>
</head>

<frameset cols="25%,*">
	<frame src="frameMngtMenu.php?SID=<? print $sid;?>&id=<? print $id;?>" name="index1">
	<frame name="view">
</frameset>

</html>
