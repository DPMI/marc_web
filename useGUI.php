<?
require("sessionCheck.php");
require("config.php");

$ID=		$_GET["ID"];


if($status < $accesslevel) {
	print "<h1>ERROR: You cant assign a user a better access level than you have.</h1>";
	print "Logged and noted.";
	exit;
}


$Connect = mysqli_connect($DB_SERVER, $user, $password) or die ("Cant connect to MySQL at $DB_SERVER");
mysqli_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");



$sql_update="UPDATE guiconfig SET selected='0' WHERE id='$selectedID'";
$result=mysqli_query($sql_update);
if(!$result) {
	print "MySQL error: " . mysqli_error();
	exit;
}

$sql_update="UPDATE guiconfig SET selected='1' WHERE id='$ID'";
$result=mysqli_query($sql_update);
if(!$result) {
	print "MySQL error: " . mysqli_error();
	exit;
}


//header("Location: root.php?SID=$sidVAR");

$sql_update="SELECT * FROM guiconfig WHERE selected=1";
//print "sql_update : $sql_update <br>\n";
$result=mysqli_query($sql_update);
if(!$result) {
	print "MySQL error: " . mysqli_error();
	exit;
}


if(mysqli_num_rows($result)>0) {
	$row = mysqli_fetch_array($result);
} else { // PRoblems. Use some default

}

$selectedID=$row["id"];
$pageStyle=$row["pageStyle"];
$pageStyleBad=$row["pageStyleBad"];
$projectName=$row["projectName"];

?>
<html>
<?
print $pageStyle;
//print "sql: $sql_update <br>\n";
?>

Update complete

</html>