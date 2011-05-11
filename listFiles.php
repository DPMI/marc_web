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
if (isset($_SESSION["accesslevel"])) {
	$level=$_SESSION["accesslevel"];
} else {
	$level=0;
}

$sql_query="SELECT * FROM files WHERE accesslevel<= " . $level;
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
	print "<th><b><a href=\"listFiles.php?SID=$sid&order=filename\">Filename</b></th>";
	print "<th><b><a href=\"listFiles.php?SID=$sid&order=accesslevel\">Access Level</a></b></th>\n";
	print "<th><b><a href=\"listFiles.php?SID=$sid&order=date\">Date</a></b></th>\n";
	print "<th><b><a href=\"listFiles.php?SID=$sid&order=filesize\">Filesize</b></th>";
	print "<th><b><a href=\"listFiles.php?SID=$sid&order=filetype\">Filetype</a></b></th>\n";
	print "<th><b><a href=\"listFiles.php?SID=$sid&order=downloads\">downloads</b></th>";
	print "<th><b><a href=\"listFiles.php?SID=$sid&order=description\">description</a></b></th>\n";

	while($row = mysql_fetch_array($result)) {
		$noEthers=0;
		if($toggle==0) {
			$color="CCCCCC";
			$toggle=1;
		} else {
			$color="DDDDDD";
			$toggle=0;
		}	

		print 	"<tr bgcolor='$color' >
		<td><a href='uploadedfiles/". $row["filename"] . "'>" . $row["filename"] . "</a></td>
		<td>". $row["accesslevel"] ."</td>
		<td>". $row["date"] ."</td>
		<td>". $row["filesize"] . "</td>
		<td>". $row["filetype"] ."</td>
		<td>". $row["downloads"] . "</td>
		<td>". $row["description"] ."</td>
		</tr>\n";
	}
	print "</table>";
} else {
	print "No files yet.";
	exit;
}


?>
</body></html>