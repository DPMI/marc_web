<?
require("sessionCheck.php");
require("config.inc");
require("model/MP.php");

$Connect = mysql_connect($DB_SERVER, $user, $password) or die ("Cant connect to MySQL at $DB_SERVER");
mysql_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");

$ID=$_GET["id"];
$mp = MP::from_id($ID);
if ( !$mp ){
  die("invalid mp");
}

$MAMPid = $mp->generate_mampid();
$mp->commit();

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
	die("Mysql Problems: " . mysql_error() . "<br>\n");
}

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
	die("Mysql Problems: " . mysql_error() . "<br>\n");
}

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
	die("Mysql Problems: " . mysql_error() . "<br>\n");
}

$MAMPidCIl="$MAMPid"."_CIload";

$sql_create = "CREATE TABLE `$MAMPidCIl` ( `id` INT NOT NULL AUTO_INCREMENT, `time` timestamp(14) NOT NULL, `noFilters` INT NOT NULL, `matchedPkts` INT NOT NULL ";
for($i=0;$i<$mp->noCI;$i++){
  $sql_create = $sql_create . ",`CI$i` VARCHAR(20) NOT NULL, `PKT$i` INT NOT NULL, `BU$i` INT NOT NULL";
}
$sql_create = $sql_create . ", INDEX( `id` ))";
$result=mysql_query ($sql_create);
if(!$result) {
	die("Mysql Problems: " . mysql_error() . "<br>\n");
}

/* tell the MP that is has been authorized. */
$mp->auth();

/* go back to MP list */
header("Location: listMPs.php");

?>