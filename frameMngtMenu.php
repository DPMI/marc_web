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
<? 
print $pageStyle;
?>

<h2><u><? print $projectName . " - Member"; ?></u></h2>
<hr>
<ul>
<?
$Connect = mysql_connect($DB_SERVER, $user, $password) or die ("Cant connect to MySQL at $DB_SERVER");
mysql_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");

$sql_question="SELECT * FROM mainmenu WHERE accesslevel<=" . $_SESSION["accesslevel"] ." ORDER BY id ASC";
//print "SQL: $sql_question <br>\n";
$tabell_query=mysql_query(($sql_question),$Connect) or die("Invalid SQL query: $sql_question");

while($row = mysql_fetch_array($tabell_query)) {
//	print "type = " . $row["type"] . "<br>\n";
if ($row["type"]==0) {
print "<li><a href='displayPage.php?url=". $row["url"] ."?SID=$sid' target=view>" .$row["string"] ."</a></li>\n";
} else if ($row["type"]==1) {
print "<li><a href='". $row["url"] ."?SID=$sid' target=view>" .$row["string"] ."</a></li>\n";
} else if ($row["type"]==2) {
print "<li><a href='". $row["url"] ."?SID=$sid' target=view>" .$row["string"] ."</a></li>\n";
}


}//while
print "</ul>\n";
?>

<h4>Site Maintenance</h4>
<hr>
<ul>
<li><a href="listPages.php?SID=<? print $sid;?>" target="view">List Pages</a></li>
<li><a href="uploadscript.php?SID=<? print $sid;?>" target="view">Upload File</a></li>
</ul>

<?
if ($_SESSION["accesslevel"]>1) {
?>

<h4>Site Administration</h4><hr>
<ul>
<li><a href="addPage.php?SID=<? print $sid;?>" target="view">Add Page</a></li>
<li><a href="listGUIconfig.php?SID=<? print $sid;?>" target="view">List GUI config</a></li>
<li><a href="addGUI.php?SID=<? print $sid;?>" target="view">Add GUI config</a></li>
<li><a href="listMenu.php?SID=<? print $sid;?>" target="view">List Menu</a></li>
<li><a href="addMenu.php?SID=<? print $sid;?>" target="view">Add Menu Entry</a></li>
<li><a href="listAccounts.php?SID=<? print $sid;?>" target="view">List Accounts</a></li>
<li><a href="addAccount.php?SID=<? print $sid;?>" target="view">Add Account</a></li>
</ul>
<?
} // end if accesslevel>1
?>
<li><a href="logout.php?SID=<? print $sid;?>" target="_top">Logout</a></li>

<p>Maintained by <a href="mailto:patrik.carlsson@bth.se">Patrik Carlsson</a>

</body>
</html>
