<?

require("config.inc");

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
$Connect = mysql_connect($DB_SERVER, $user, $password) or die ("Cant connect to MySQL at $DB_SERVER");
mysql_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");
?>
<table><tr><td width=180>
<h2><u><? print $projectName; ?></u></h2>
<ul>
<?
$sql_question="SELECT * FROM mainmenu WHERE accesslevel='0'ORDER BY id asc";
//print "SQL: $sql_question <br>\n";
$tabell_query=mysql_query(($sql_question),$Connect) or die("Invalid SQL query: $sql_question");

while($row = mysql_fetch_array($tabell_query)) {
if ($row["type"]==0) {
print "<img src='http://www.bth.se/bth/images/eng/arrow_link.gif'><a href='displayPage.php?url=". $row["url"] ."' target=view>" .$row["string"] ."</a><br>";
} else if ($row["type"]==1) {
print "<img src='http://www.bth.se/bth/images/eng/arrow_link.gif'><a href='". $row["url"] ."' target=view>" .$row["string"] ."</a><br>";
} else if ($row["type"]==2) {
print "<img src='http://www.bth.se/bth/images/eng/arrow_link.gif'><a href='". $row["url"] ."' target=view>" .$row["string"] ."</a><br>";
}
}//while
print "</ul>\n";

mysql_close($Connect);
?>
<ul>
<li><a href="login.php" target="_top">Login</a>[Requires account]</li>
</ul>

<a href='index.html' target=_top>Home</a>
</td></tr>
<tr><td height=600></td></tr>
</table>
</html>