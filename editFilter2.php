<?
require("sessionCheck.php");
require("config.php");
require_once('model/MP.php');
require_once('model/Filter.php');

if ( isset($_POST['cancel']) ){
  header("Location: listFilters.php?SID=$sid");
  exit;
}

$mp = MP::from_mampid($_POST['mp']);
if ( $mp == null ){
  die("No measurement point named {$_GET['MAMPid']}!");
}

$FILTER_ID=$_POST["filter_id"];
$OLD_FILTER_ID=$_POST["old_filter_id"];

$fields = $_POST;
unset($fields['old_filter_id']);
unset($fields['mp']);
unset($fields['action']);
foreach ($fields as $key => $value){
  if ( strcmp(substr($key, -10), '_selection') == 0 || strcmp(substr($key, -3), '_cb') == 0 ){
    unset($fields[$key]);
  }
}

$filter = new Filter($mp, $fields);
$filter->commit($OLD_FILTER_ID > 0 ? $OLD_FILTER_ID : null);

$mp->reload_filter($FILTER_ID);
header("Location: listFilters.php");

?>