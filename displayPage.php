<?
require("sessionCheck.php");
require("config.php");

$Connect = mysql_connect($DB_SERVER, $user, $password) or die ("Cant connect to MySQL at $DB_SERVER");
mysql_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");

$url=$_GET["url"];
$qMark=strpos($url,"?");
if($qMark>0) {
  $url=substr($url, 0, $qMark);
}

$sql_question="SELECT * FROM pages WHERE url='" . $url . "'";
$tabell_query=mysql_query(($sql_question),$Connect) or die("Invalid SQL query: $sql_question");
$row = mysql_fetch_array($tabell_query);

$accesslevel = isset($_SESSION["accesslevel"]) ? $_SESSION["accesslevel"] : 0;

if ( $row["accesslevel"] > $accesslevel ) {
  header("Location: loginDenied.php");
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en-US" xml:lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <link rel="shortcut icon" type="image/x-icon" href="http://www.bth.se/favicon.ico" />
    <title>MArC</title>
  </head>
  
  <body class="bthcss">
    <div id="content">
      <?=$row['text']?>
      <hr />
      <p>Last Modified: <?=$row['date']?></p>
    </div>
  </body>
</html>
<?
mysql_close($Connect);
?>
