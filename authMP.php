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

$tables[] = "CREATE TABLE `{$MAMPid}_filterlist` (
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

$tables[] = "CREATE TABLE `{$MAMPid}_filterlistverify` (
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
  `DESTADDR` varchar(23) NOT NULL default '',
  `comment` varchar(17) NOT NULL default '',
  `TYPE` int(11) NOT NULL default '0',
  `CAPLEN` int(11) NOT NULL default '0',
  `consumer` int(11) NOT NULL default '0'
) TYPE=MyISAM";
    
$tables[] = "CREATE TABLE `{$MAMPid}_ci` ( `id` INT NOT NULL AUTO_INCREMENT ,
        `ci` INT NOT NULL ,
        `type` TEXT NOT NULL ,
        `mtu` VARCHAR( 20 ) NOT NULL ,
        `speed` VARCHAR( 50 ) NOT NULL ,
        `comments` TEXT NOT NULL ,
        INDEX ( `id` ) )";


$MAMPidCIl="$MAMPid"."_CIload";
$sql_create = "CREATE TABLE `$MAMPidCIl` ( `id` INT NOT NULL AUTO_INCREMENT, `time` timestamp(14) NOT NULL, `noFilters` INT NOT NULL, `matchedPkts` INT NOT NULL ";
for($i=0;$i<$mp->noCI;$i++){
  $sql_create = $sql_create . ",`CI$i` VARCHAR(20) NOT NULL, `PKT$i` INT NOT NULL, `BU$i` INT NOT NULL";
}
$sql_create = $sql_create . ", INDEX( `id` ))";
$tables[] = $sql_create;

/* Create SQL tables */
foreach ( $tables as $query ){
  if ( !mysql_query ($query) ){
    echo "<h1>SQL error</h1>\n";
    echo "<p>\"" . mysql_error() . "\"<p>\n";
    echo "<p>The attempted query was:</p>\n";
    echo "<pre>$query</pre>";
    exit;
  }
}

/* tell the MP that is has been authorized. */
$mp->auth();

/* go back to MP list */
header("Location: listMPs.php");

?>