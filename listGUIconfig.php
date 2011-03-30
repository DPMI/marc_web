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

$Connect = mysql_connect($DB_SERVER, $user, $password) or die ("Cant connect to MySQL at $DB_SERVER");
mysql_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");

$toggle=0;

$order=$HTTP_GET_VARS["order"];

$sql_query="SELECT * FROM guiconfig";
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

	print "<th><b><a href=\"listGUIconfig.php?SID=$sid&order=id\">id</a></b></th>";

	print "<th><b>Selected</b></th>";
	print "<th><b>pageStyle</b></th>";
	print "<th><b>pageStyleBad</b></th>";
	print "<th><b>projectName</b></th>";

	print "<th></b>Edit</b></th>";
	print "<th></b>Use Config</b></th></tr>\n";
	while($row = mysql_fetch_array($result)) {
		$noEthers=0;
		if($toggle==0) {
			$color="CCCCCC";
			$toggle=1;
		} else {
			$color="DDDDDD";
			$toggle=0;
		}	
		print "<tr bgcolor='$color' >";
		print "<td>". $row["id"] . "</td>";
		print "<td>". $row["selected"] . "</td>";
		print "<td><textarea rows=10 cols=40>". $row["pageStyle"] ."</textarea></td>";
		print "<td><textarea rows=10 cols=40>". $row["pageStyleBad"] . "</textarea></td>";
		print "<td>". $row["projectName"] . "</td>";
		print "<td><a href=\"editGUI.php?SID=$sid&ID=" . $row["id"] ."&selectedid=" . $selectedID ." \">EDIT</a></td>";
		print "<td><a href=\"useGUI.php?SID=$sid&ID=" . $row["id"] ."&selectedid=" . $selectedID ." \">USE</a></td></tr>\n";
	}
	print "</table>";
} else {
	print "No entries in the access table, How the hell did you get here!?!?!? ";
	exit;
}


?>
</body></html>