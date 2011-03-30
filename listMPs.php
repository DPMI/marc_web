<?
require("sessionCheck.php");
require("config.inc");


$sid=$_GET["SID"];
if (isset($sid)) {
	$nSid=session_id();
	if($sid!=$nSid) {
		print "The passes SID is not equal to the one found here.. problems!";
//		print "$sid == $nSid <br>\n";
			exit();
	}
} 

?>
<html>
<? 
print $pageStyle;

$Connect = mysql_connect($DB_SERVER, $user, $password) or die ("Cant connect to MySQL at $DB_SERVER");
mysql_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");

$toggle=0;

$order=$_GET["order"];
if (isset($_SESSION["accesslevel"])) {
	$level=$_SESSION["accesslevel"];
} else {
	$level=0;
}

$sql_query="SELECT * FROM measurementpoints";
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
	print "<th><b>Status</b></th>";
	print "<th><b><a href=\"listMPs.php?SID=$sid&order=id\">ID</b></th>";
	print "<th><b><a href=\"listMPs.php?SID=$sid&order=name\">name</a></b></th>\n";
	print "<th><b><a href=\"listMPs.php?SID=$sid&order=ip\">ip</a></b></th>\n";
	print "<th><b><a href=\"listMPs.php?SID=$sid&order=port\">ip</a></b></th>\n";
	print "<th><b><a href=\"listMPs.php?SID=$sid&order=mac\">mac</b></th>";
	print "<th><b><a href=\"listMPs.php?SID=$sid&order=comment\">comment</a></b></th>\n";
	print "<th><b><a href=\"listMPs.php?SID=$sid&order=time\">time</b></th>";
	print "<th><b><a href=\"listMPs.php?SID=$sid&order=MAMPid\">MAMPid</a></b></th>\n";
	print "<th><b><a href=\"listMPs.php?SID=$sid&order=maxFilters\">maxFilters</a></b></th>\n";
	print "<th><b>Authorize MP</a></b></th>\n";

	while($row = mysql_fetch_array($result)) {
		$noEthers=0;
		if($toggle==0) {
			$color="CCCCCC";
			$toggle=1;
		} else {
			$color="DDDDDD";
			$toggle=0;
		}	

		print 	"<tr bgcolor='$color' >";
	
	if(strlen($row["MAMPid"])>0) {
		$sql2="SELECT * FROM " . $row["MAMPid"] ."_filterlist";
		$result2=mysql_query($sql2);
		if(!$result2){
			print "<td> MySQL pr. " . mysql_error() ."</td>";
		} else {
			if(mysql_num_rows($result2)>0) {
				print "<td>Capturing</td>";
			} else {
				print "<td>Idle</td>";
			}
		}
	} else {
		print "<td>Not Auth</td>";
	}

	print	"<td>". $row["id"] . "</a></td>
		<td>". $row["name"] . "</a></td>
		<td>". $row["ip"] . "</a></td>
		<td>". $row["port"] . "</a></td>
		<td>". $row["mac"] ."</td>
		<td>". $row["comment"] . "</td>
		<td>". $row["time"] ."</td>
		<td>". $row["MAMPid"] . "</td>
		<td>". $row["maxFilters"] ."</td>";
	if(strlen($row["MAMPid"])>0) {
		print "<td>Authorized</td></tr>\n";
	} else { 			
		print "<td><a href=\"authMP.php?SID=$sid&id=". $row["id"] ."\">Auth</a></td></tr>\n";
	}
	}
	print "</table>";
} else {
	print "No files yet.";
	exit;
}


?>
</body></html>