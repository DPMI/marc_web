<?php
require("sessionCheck.php");
require("config.php");
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

<h2><u><? print $projectName . " - Member"; ?></u></h2>
<hr>
<ul>
<?
$Connect = mysqli_connect($DB_SERVER, $user, $password) or die ("Cant connect to MySQL at $DB_SERVER");
mysqli_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");

$sql_question="SELECT * FROM mainmenu WHERE accesslevel <= $u_access ORDER BY id ASC";
$tabell_query=mysqli_query(($sql_question),$Connect) or die("Invalid SQL query: $sql_question");

while($row = mysqli_fetch_array($tabell_query)) { ?>
<?php  if ($row["type"]==0) { ?>
  <li><a href="displayPage.php?url=<?=$row["url"]?>" target="view"><?=$row["string"]?></a></li>
<?php } else if ($row["type"]==1) { ?>
  <li><a href="<?=$row["url"]?>" target="view"><?=$row["string"]?></a></li>
<?php } else if ($row["type"]==2) { ?>
  <li><a href="<?=$row["url"]?>" target="view"><?=$row["string"]?></a></li>
<?php }
}//while
print "</ul>\n";
?>

<h4>Site Maintenance</h4>
<hr>
<ul>
<li><a href="listPages.php" target="view">List Pages</a></li>
<li><a href="uploadscript.php" target="view">Upload File</a></li>
</ul>

<?
if ($_SESSION["accesslevel"]>1) {
?>

<h4>Site Administration</h4><hr>
<ul>
<li><a href="addPage.php" target="view">Add Page</a></li>
<li><a href="listGUIconfig.php" target="view">List GUI config</a></li>
<li><a href="addGUI.php" target="view">Add GUI config</a></li>
<li><a href="listMenu.php" target="view">List Menu</a></li>
<li><a href="addMenu.php" target="view">Add Menu Entry</a></li>
<li><a href="listAccounts.php" target="view">List Accounts</a></li>
<li><a href="addAccount.php" target="view">Add Account</a></li>
</ul>
<?
} // end if accesslevel>1
?>
<li><a href="logout.php" target="_top">Logout</a></li>

<p>Maintained by <a href="mailto:patrik.carlsson@bth.se">Patrik Carlsson</a>

</body>
</html>
