<?
require("config.php");

$Connect = mysqli_connect($DB_SERVER, $user, $password) or die ("Cant connect to MySQL at $DB_SERVER");
mysqli_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");
?>
<h2><u><? print $projectName; ?></u></h2>
<ul>
<?
$sql_question="SELECT * FROM mainmenu WHERE accesslevel='0'ORDER BY id asc";
//print "SQL: $sql_question <br>\n";
$tabell_query=mysqli_query(($sql_question),$Connect) or die("Invalid SQL query: $sql_question");

while($row = mysqli_fetch_array($tabell_query)) {
if ($row["type"]==0) {
	print "<img src='http://www.bth.se/bth/images/eng/arrow_link.gif'><a href='displayPage.php?url=". $row["url"] ."' target=view>" .$row["string"] ."</a><br />";
} else if ($row["type"]==1) {
	print "<img src='http://www.bth.se/bth/images/eng/arrow_link.gif'><a href='". $row["url"] ."' target=view>" .$row["string"] ."</a><br />";
} else if ($row["type"]==2) {
	print "<img src='http://www.bth.se/bth/images/eng/arrow_link.gif'><a href='". $row["url"] ."' target=view>" .$row["string"] ."</a><br />";
}
}//while
print "</ul>\n";

mysqli_close($Connect);
?>
<ul>
<li><a href="login.php" target="_top">Login</a>[Requires account]</li>
</ul><br />


