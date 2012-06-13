<?
require("sessionCheck.php");
require("config.php");
?>
<html>
<?
print $pageStyle;
?>



<?
require("config.php");

$Connect = mysql_connect($DB_SERVER, $user, $password) or die ("Cant connect to MySQL at $DB_SERVER");
mysql_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");

$toggle=0;

$order=$_GET["order"];

$sql_query="SELECT * FROM access";
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
	print "<th><b><a href=\"listAccounts.php?SID=$sid&order=uname\">Uname</a></b></th>";
	print "<th><b><a href=\"listAccounts.php?SID=$sid&order=passwd\">Passwd</a></b></th>";
	print "<th><b><a href=\"listAccounts.php?SID=$sid&order=status\">Status</a></b></th>";
	print "<th><b><a href=\"listAccounts.php?SID=$sid&order=comment\">Comment</b></th>";
	print "<th><b><a href=\"listAccounts.php?SID=$sid&order=time\">Date/Time</a></b></th>";
	print "<th><b><a href=\"listAccounts.php?SID=$sid&order=name\">Name</b></th>";
	print "<th><b><a href=\"listAccounts.php?SID=$sid&order=email\">Date/E-Mail</a></b></th>";

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

		print 	"<tr bgcolor='$color' ><td>". $row["uname"] . "</td><td>". $row["passwd"] . "</td><td>". $row["status"] ."</td>";
		print "<td>". $row["comment"] . "</td><td>". $row["time"] . "</td><td>". $row["Name"] . "</td><td>". $row["Email"];
		print "</td><td><a href=\"editUser.php?SID=$sid&ID=" . $row["id"] ." \">EDIT</a></td></tr>\n";
	}
	print "</table>";
} else {
	print "No entries in the access table, How the hell did you get here!?!?!? ";
	exit;
}


?>
</body></html>