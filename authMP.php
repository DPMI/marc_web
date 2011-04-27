<?
require("sessionCheck.php");
require("config.inc");


$sid=$_GET["SID"];
if (isset($sid)) {
	$nSid=session_id();
	if($sid!=$nSid) {
		print "The passes SID is not equal to the one found here.. problems!";
//		print "$sid == $nSid <br>\n";
			exit();
	}
} 
else {
	print "<html><head>\n";
	print "<title>404 Not Found</title>\n";
	print "</head><body>\n";
	print "<h1>Not Found</h1>\n";
	print "<p>The requested URL was not found on this server.</p>\n";
	print "<hr/>\n";
	print "<address>Apache/2.0.48 (Unix) DAV/2 PHP/4.3.4 Server at inga.its.bth.se Port 80</address>\n";
	print "</body></html>\n";
	exit();
}

?>
<html>
<? 
print $pageStyle;
?>



<?
require("config.inc");

$Connect = mysql_connect($DB_SERVER, $user, $password) or die ("Cant connect to MySQL at $DB_SERVER");
mysql_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");

$ID=$_GET["id"];


if (isset($_SESSION["accesslevel"])) {
	$level=$_SESSION["accesslevel"];
} else {
	$level=0;
}

$sql_query="SELECT * FROM measurementpoints where id=$ID";

$result=mysql_query ($sql_query);
if(!$result) {
	print "sq: $sql_q <br>\n";
	print "Mysql Problems: " . mysql_error() . "<br>\n";
}
$row = mysql_fetch_array($result);

$MAMPid=$row["name"].substr($row["mac"],15,2);
print "The MAMPid will be $MAMPid <br>\n";
$sql_insert="UPDATE measurementpoints SET MAMPid='$MAMPid' WHERE id=$ID";
$result=mysql_query ($sql_insert);
if(!$result) {
	print "sq: $sql_insert <br>\n";
	print "Mysql Problems: " . mysql_error() . "<br>\n";
}
print "Updated measurementpoints <br>\n";

$MAMPidfl="$MAMPid"."_filterlist";

$sql_create="CREATE TABLE `$MAMPidfl` (
  `filter_id` int(11) PRIMARY KEY,
  `ind` bigint(20) NOT NULL default '0',
  `CI_ID` varchar(8) NOT NULL default '',
  `VLAN_TCI` int(11) NOT NULL default '0',
  `VLAN_TCI_MASK` int(11) NOT NULL default '0',
  `ETH_TYPE` int(11) NOT NULL default '0',
  `ETH_TYPE_MASK` int(11) NOT NULL default '0',
  `ETH_SRC` varchar(17) NOT NULL default '',
  `ETH_SRC_MASK` varchar(17) NOT NULL default '',
  `ETH_DST` varchar(17) NOT NULL default '',
  `ETH_DST_MASK` varchar(17) NOT NULL default '',
  `IP_PROTO` int(11) NOT NULL default '0',
  `IP_SRC` varchar(16) NOT NULL default '',
  `IP_SRC_MASK` varchar(16) NOT NULL default '',
  `IP_DST` varchar(16) NOT NULL default '',
  `IP_DST_MASK` varchar(16) NOT NULL default '',
  `SRC_PORT` int(11) NOT NULL default '0',
  `SRC_PORT_MASK` int(11) NOT NULL default '0',
  `DST_PORT` int(11) NOT NULL default '0',
  `DST_PORT_MASK` int(11) NOT NULL default '0',
  `consumer` int(11) NOT NULL default '0',
  `DESTADDR` varchar(23) NOT NULL default '',
  `TYPE` int(11) NOT NULL default '1',
  `CAPLEN` int(11) NOT NULL default '0'
) TYPE=MyISAM";
    
$result=mysql_query ($sql_create);
if(!$result) {
	print "sq: $sql_create <br>\n";
	print "Mysql Problems: " . mysql_error() . "<br>\n";
}
print "Created $MAMPidfl <br>\n";

$MAMPidflV="$MAMPid"."_filterlistverify";

$sql_create="CREATE TABLE `$MAMPidflV` (
  `filter_id` int(11) NOT NULL default '0',
  `ind` bigint(20) NOT NULL default '0',
  `CI_ID` varchar(8) NOT NULL default '',
  `VLAN_TCI` int(11) NOT NULL default '0',
  `VLAN_TCI_MASK` int(11) NOT NULL default '0',
  `ETH_TYPE` int(11) NOT NULL default '0',
  `ETH_TYPE_MASK` int(11) NOT NULL default '0',
  `ETH_SRC` varchar(17) NOT NULL default '',
  `ETH_SRC_MASK` varchar(17) NOT NULL default '',
  `ETH_DST` varchar(17) NOT NULL default '',
  `ETH_DST_MASK` varchar(17) NOT NULL default '',
  `IP_PROTO` int(11) NOT NULL default '0',
  `IP_SRC` varchar(16) NOT NULL default '',
  `IP_SRC_MASK` varchar(16) NOT NULL default '',
  `IP_DST` varchar(16) NOT NULL default '',
  `IP_DST_MASK` varchar(16) NOT NULL default '',
  `SRC_PORT` int(11) NOT NULL default '0',
  `SRC_PORT_MASK` int(11) NOT NULL default '0',
  `DST_PORT` int(11) NOT NULL default '0',
  `DST_PORT_MASK` int(11) NOT NULL default '0',
  `DESTADDR` varchar(23) NOT NULL default '',
  `comment` varchar(17) NOT NULL default '',
  `TYPE` int(11) NOT NULL default '0',
  `CAPLEN` int(11) NOT NULL default '0',
  `consumer` int(11) NOT NULL default '0',
  KEY `filter_id` (`filter_id`)
) TYPE=MyISAM";
    
$result=mysql_query ($sql_create);
if(!$result) {
	print "sq: $sql_create <br>\n";
	print "Mysql Problems: " . mysql_error() . "<br>\n";
}
print "Created $MAMPidflV <br>\n";

$MAMPidci="$MAMPid"."_ci";
$sql_create = "CREATE TABLE `$MAMPidci` ( `id` INT NOT NULL AUTO_INCREMENT ,
        `ci` INT NOT NULL ,
        `type` TEXT NOT NULL ,
        `mtu` VARCHAR( 20 ) NOT NULL ,
        `speed` VARCHAR( 50 ) NOT NULL ,
        `comments` TEXT NOT NULL ,
        INDEX ( `id` ) )";
$result=mysql_query ($sql_create);
if(!$result) {
	print "sq: $sql_create <br>\n";
	print "Mysql Problems: " . mysql_error() . "<br>\n";
}
print "Created $MAMPidci <br>\n";

$MAMPidCIl="$MAMPid"."_CIload";

$sql_create = "CREATE TABLE `$MAMPidCIl` ( `id` INT NOT NULL AUTO_INCREMENT, `time` timestamp(14) NOT NULL, `noFilters` INT NOT NULL, `matchedPkts` INT NOT NULL ";
for($i=0;$i<$row["noCI"];$i++){
  $sql_create = $sql_create . ",`CI$i` VARCHAR(20) NOT NULL, `PKT$i` INT NOT NULL, `BU$i` INT NOT NULL";
}
$sql_create = $sql_create . ", INDEX( `id` ))";
$result=mysql_query ($sql_create);
if(!$result) {
	print "sq: $sql_create <br>\n";
	print "Mysql Problems: " . mysql_error() . "<br>\n";
}
print "Created $MAMPidCIl <br>\n";

print "DATABASE is updated. <br>\n";
print "Lets talk tell the client.<br>\n";
$IP=$row["ip"];
$port=$row["port"];

$message=sprintf("%s%s",pack("N",1),$MAMPid);

print "Contacting ctrl $IP:$port <h1>$message</h1>\n";
$fp = fsockopen("udp://$IP", $port, $errno, $errstr);
if (!$fp) {
   echo "ERROR: $errno - $errstr<br />\n";
} else {
   fwrite($fp, $message);
   fclose($fp);
}





?>
</body></html>