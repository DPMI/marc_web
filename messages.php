<?

require("config.php");

?>
<html>
<?
print $pageStyle;
?>
<h2>Messages</h2>
<?

$Connect = mysqli_connect($DB_SERVER, $user, $password) or die ("Cant connect to MySQL at $DB_SERVER");
mysqli_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");


$sql_question="SELECT UNIX_TIMESTAMP(date) AS date, id, text FROM greeting order by id desc LIMIT 0,3";
$tabell_query=mysqli_query(($sql_question),$Connect) or die("Invalid SQL query: $sql_question");
$tabell=mysqli_fetch_array($tabell_query);

print "<table border='1'width='100%' ALIGN='LEFT'>\n";
while(!($tabell==0)){
	print '<tr><td>'. date('Y-m-d', $tabell["date"]) . '</td><td> '. $tabell["text"] .'</td></tr>';
	print "\n";
	$tabell=mysqli_fetch_array($tabell_query);
}
print "</table>\n";
?>
</html>
