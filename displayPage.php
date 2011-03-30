<?
require("sessionCheck.php");
require("config.inc");

$Connect = mysql_connect($DB_SERVER, $user, $password) or die ("Cant connect to MySQL at $DB_SERVER");
mysql_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");

$url=$HTTP_GET_VARS["url"];
//print "url = $url <br>\n";
$qMark=strpos($url,"?");
//print "qMark = $qMark <br>\n";
if($qMark>0) {
	$url=substr($url, 0, $qMark);
}

$sql_question="SELECT * FROM pages WHERE url='" . $url . "'";
//print "SQL: $sql_question <br>\n";
$tabell_query=mysql_query(($sql_question),$Connect) or die("Invalid SQL query: $sql_question");
$row = mysql_fetch_array($tabell_query);


if (isset($_SESSION["accesslevel"])) {
	if ($row["accesslevel"]>$_SESSION["accesslevel"]) {
		header("Location: loginDenied.php");
	}
} else {
	if ($row["accesslevel"]>0) {
		header("Location: loginDenied.php");
	}
}	



?>
<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
   <meta name="Author" content="Patrik Carlsson">
   <meta name="GENERATOR" content="Mozilla/4.75 [en] (Windows NT 5.0; U) [Netscape]">
   <title>Root</title>
</head>
<? 
print $pageStyle;
?>
<?

	print $row["text"];
	print "<hr>\n";
	print "Last Modified: " . $row["date"] . "<br>\n";
	

mysql_close($Connect);
?>
</html>