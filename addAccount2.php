<?
require("sessionCheck.php");
require("config.php");
$uname=		$HTTP_POST_VARS["uname"];
$passwd=	$HTTP_POST_VARS["passwd"];
$status=	$HTTP_POST_VARS["status"];
$comment=	$HTTP_POST_VARS["comment"];
$name=		$HTTP_POST_VARS["name"];
$email=		$HTTP_POST_VARS["email"];

if($status < $accesslevel) {
	print "<h1>ERROR: You cant assign a user a better access level than you have.</h1>";
	print "Logged and noted.";
	exit;
}


$Connect = mysqli_connect($DB_SERVER, $user, $password) or die ("Cant connect to MySQL at $DB_SERVER");
mysqli_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");

$sql_update="INSERT INTO access SET uname='$uname', passwd=PASSWORD('$passwd'), status='$status', comment='$comment', name='$name', email='$email'";
$result=mysqli_query($sql_update);
if(!$result) {
	print "MySQL error: " . mysqli_error();
	exit;
}
//header("Location: root.php?SID=$sidVAR");

?>
<?
print $pageStyle;
?>


Add complete

</html>