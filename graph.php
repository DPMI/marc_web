<?php

require("sessionCheck.php");
require("config.php");
require('model/MP.php');

$mampid = $_GET['mampid'];
$ci = isset($_GET['CI']) ? $_GET['CI'] : false;
$span = isset($_GET['span']) ? $_GET['span'] : '24h';
$mp = MP::from_mampid($mampid);
/**@todo handle all error conditions */

chdir($rrdbase);

$filebase = "$mampid";
$title = $mp->name;
if ( $ci !== false ){
  $filebase = "{$mampid}_CI$ci";
  $title = "{$mp->name} CI$ci";
}

$filename = "{$filebase}_{$span}.png";
$regen = true;
$stat = @stat($filename);
$regen = $stat == false || (time() - $stat['mtime'] > 5*60);

if ( $regen ){
  $argv = array(
    "rrdtool", "graph", 
    "$filename",
    "-a", "PNG",
    "--title", $title,
    "--vertical-label", "pkt/sec",
    "--start", "end-$span",
    "DEF:total=$filebase.rrd:total:AVERAGE", "VDEF:total_last=total,TOTAL",
    "DEF:matched=$filebase.rrd:matched:AVERAGE", "VDEF:matched_last=matched,TOTAL",
    "LINE1:total#ff0000:Received:",
    "GPRINT:total_last:%.0lf pkts",
    "AREA:matched#00ff00:Matched",
    "GPRINT:matched_last:%.0lf pkts"
  );
  $cmd = "'" . implode("' '", $argv) . "'";
  exec("$cmd 2>&1", $output, $rc);
  if ( $rc != 0 ){
    echo "<h1>RRDtool error</h1>\n";
    echo "<p>Command: \"$cmd\"<br/>Returncode: $rc<p>\n";
    echo "<p>Output:</p>\n";
    echo "<pre>" . implode("\n", $output) . "</pre>";
    exit;
  }
}

header ("Content-type: image/png");
echo file_get_contents($filename);

?>