<?
require("sessionCheck.php");
require("config.php");
require("model/MP.php");

$Connect = mysqli_connect($DB_SERVER, $user, $password,$DATABASE) or die ("Cant connect to MySQL at $DB_SERVER");
//mysqli_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");

$ID=$_GET["id"];
$mp = MP::from_id($ID);
if ( !$mp ){
  die("invalid mp");
}

$MAMPid = $mp->generate_mampid();
$iface = explode(";", $mp->CI_iface);
$mp->commit();

$tables[] = "CREATE TABLE IF NOT EXISTS `{$MAMPid}_ci` ( `id` INT NOT NULL AUTO_INCREMENT ,
        `ci` INT NOT NULL ,
        `type` TEXT NOT NULL ,
        `mtu` VARCHAR( 20 ) NOT NULL ,
        `speed` VARCHAR( 50 ) NOT NULL ,
        `comments` TEXT NOT NULL ,
        INDEX ( `id` ) )";

$MAMPidCIl="$MAMPid"."_CIload";
$sql_create = "CREATE TABLE IF NOT EXISTS `$MAMPidCIl` ( `id` INT NOT NULL AUTO_INCREMENT, `time` timestamp NOT NULL, `noFilters` INT NOT NULL, `matchedPkts` INT NOT NULL ";
for($i=0;$i<$mp->noCI;$i++){
  $sql_create = $sql_create . ",`CI$i` VARCHAR(20) NOT NULL, `PKT$i` INT NOT NULL, `BU$i` INT NOT NULL";
}
$sql_create = $sql_create . ", INDEX( `id` ))";
$tables[] = $sql_create;

/* Create SQL tables */
foreach ( $tables as $query ){
  if ( !mysqli_query ($Connect, $query) ){
    echo "<h1>SQL error</h1>\n";
    echo "<p>\"" . mysqli_error() . "\"<p>\n";
    echo "<p>The attempted query was:</p>\n";
    echo "<pre>$query</pre>";
    exit;
  }
}

/* tell the MP that is has been authorized. */
$mp->auth();

/* go back to MP list */
header("Location: $index/MP");

?>