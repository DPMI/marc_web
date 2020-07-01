<?
require("sessionCheck.php");
require("config.php");

$ID=		$_GET["ID"];

$url=		$HTTP_POST_VARS["url"];
$text=		$HTTP_POST_VARS["text"];



$Connect = mysqli_connect($DB_SERVER, $user, $password) or die ("Cant connect to MySQL at $DB_SERVER");
mysqli_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");

$sql_update="UPDATE pages SET url='$url', accesslevel='$accesslevel', text='$text' WHERE id='$ID'";
$result=mysqli_query($sql_update);
if(!$result) {
	print "MySQL error: " . mysqli_error();
	exit;
}
//header("Location: root.php?SID=$sidVAR");

?>
<html>
<?
print $pageStyle;
?>

Update complete

</html>