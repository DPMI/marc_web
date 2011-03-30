<?
$uname=$_POST["uName"];
$pass=$_POST["pWord"];

require("config.inc");

$Connect = mysql_connect($DB_SERVER, $user,$password) or die ("Cant connect to MySQL at $DB_SERVER");
if(!mysql_select_db ($DATABASE,$Connect)){
	exit;
}

$sql_query="SELECT * FROM access WHERE uname='".$uname."' and passwd=PASSWORD('".$pass."')";
$result=mysql_query($sql_query) or die ("Erro query: " . mysql_error() . "<br>\n");
$n=mysql_num_rows($result);
if($n==0) {
 //Access denied!!!!
 print "SQL = " . $sql_query ."<br>\n";
//	header("Location: loginDenied.php");
} else {
 //Access allowed!!!
	session_start();
	$_SESSION["OK"]="OK";

	if (getenv(HTTP_X_FORWARDED_FOR)){ 
		$ip=getenv(HTTP_X_FORWARDED_FOR); 
	} else { 
		$ip=getenv(REMOTE_ADDR); 
	} 
	$row=mysql_fetch_array($result);
	$sidVAR=session_id();
	$SID=$sidVAR;

	$_SESSION["ip"]=$ip;;
	$_SESSION["accesslevel"]=$row["status"];
	$_SESSION["username"]=$uname;
	$_SESSION["SID"]=$SID;

	header("Location: framesMgnt.php?SID=$sidVAR");
}
?>