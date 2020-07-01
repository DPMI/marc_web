<?
require("sessionCheck.php");
require("config.php");

$url=		$_POST["url"];
$accesslevel=	$_POST["accesslevel"];
$text=		$_POST["text"];



$Connect = mysqli_connect($DB_SERVER, $user, $password,$DATABASE) or die ("Cant connect to MySQL at $DB_SERVER");
//mysqli_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");

$sql_update="INSERT pages SET url='$url', accesslevel='$accesslevel', text='$text'";
$result=mysqli_query($Connect, $sql_update);
if(!$result) {
	print "MySQL error: " . mysqli_error() . "\n";
	print "Query = $sql_update \n";
	exit;
}
//header("Location: root.php?SID=$sidVAR");

?>
<html>
<?
print $pageStyle;
?>

Insert complete

</html>