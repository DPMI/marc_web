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

$Connect = mysqli_connect($DB_SERVER, $user, $password) or die ("Cant connect to MySQL at $DB_SERVER");
mysqli_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");

$toggle=0;

$order=$_GET["order"];
if (isset($_SESSION["accesslevel"])) {
	$level=$_SESSION["accesslevel"];
} else {
	$level=0;
}

$sql_query="SELECT * FROM access";
if($order!=""){
	$sql_query=$sql_query . " ORDER BY $order";
}

$result=mysqli_query ($sql_query);
if(!$result) {
	print "sq: $sql_q <br>\n";
	print "Mysql Problems: " . mysqli_error() . "<br>\n";
}

if(mysqli_num_rows($result)>0) {
	print "<table border=0>";
	print "<th><b><a href=\"listMembers.php?SID=$sid&order=Name\">Name</b></th>";
	print "<th><b><a href=\"listMembers.php?SID=$sid&order=Email\">Email</a></b></th>\n";

	while($row = mysqli_fetch_array($result)) {
		$noEthers=0;
		if($toggle==0) {
			$color="CCCCCC";
			$toggle=1;
		} else {
			$color="DDDDDD";
			$toggle=0;
		}

		print 	"<tr bgcolor='$color' >
		<td>". $row["Name"] . "</td>
		<td>". $row["Email"] ."</td>
		</tr>\n";
	}
	print "</table>";
} else {
	print "No entries in the access table, How the hell did you get here!?!?!? ";
	exit;
}


?>
</body></html>