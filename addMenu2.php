<?
require("sessionCheck.php");
require("config.php");

$ID=		$_GET["ID"];
$string=	$HTTP_POST_VARS["string"];
$url=		$HTTP_POST_VARS["url"];
$accesslevel=	$HTTP_POST_VARS["accesslevel"];
$comment=	$HTTP_POST_VARS["comment"];
$type=		$HTTP_POST_VARS["type"];



$Connect = mysqli_connect($DB_SERVER, $user, $password) or die ("Cant connect to MySQL at $DB_SERVER");
mysqli_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");

$sql_update="INSERT mainmenu SET string='$string', url='$url', accesslevel='$accesslevel', comment='$comment', type='$type'";
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

Insert complete

</html>