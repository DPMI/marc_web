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
require("config.inc");

$Connect = mysql_connect($DB_SERVER, $user, $password) or die ("Cant connect to MySQL at $DB_SERVER");
mysql_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");

$toggle=0;

$order=$HTTP_GET_VARS["order"];

$sql_query="SELECT * FROM pages";
if($order!=""){
	$sql_query=$sql_query . " ORDER BY $order";
}	

$result=mysql_query ($sql_query);
if(!$result) {
	print "sq: $sql_q <br>\n";
	print "Mysql Problems: " . mysql_error() . "<br>\n";
}

if(mysql_num_rows($result)>0) {
	print "<table border=0>";
	print "<th><b><a href=\"listPages.php?SID=$sid&order=id\">Id</a></b></th>";
	print "<th><b><a href=\"listPages.php?SID=$sid&order=date\">Date</a></b></th>";
	print "<th><b><a href=\"listPages.php?SID=$sid&order=url\">Url</a></b></th>";
	print "<th><b><a href=\"listPages.php?SID=$sid&order=accesslevel\">Access Level</a></b></th>";
	print "<th><b><a href=\"listPages.php?SID=$sid&order=text\">Text</b></th>";
	print "<th></b>EDIT</b></th></tr>\n";
	while($row = mysql_fetch_array($result)) {
		$noEthers=0;
		if($toggle==0) {
			$color="CCCCCC";
			$toggle=1;
		} else {
			$color="DDDDDD";
			$toggle=0;
		}	

		print 	"<tr bgcolor='$color' ><td>". $row["id"] . "</td><td>". $row["date"] . "</td><td>". $row["url"] ."</td>";
		print "<td>". $row["accesslevel"] . "</td><td>". $row["text"];
		print "</td><td><a href=\"editPage.php?SID=$sid&ID=" . $row["id"] ." \">EDIT</a></td></tr>\n";
	}
	print "</table>";
} else {
	print "No entries in the access table, How the hell did you get here!?!?!? ";
	exit;
}


?>
</body></html>