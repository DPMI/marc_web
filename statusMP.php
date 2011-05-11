<?
require("sessionCheck.php");
require("config.php");
?>
<html>
<? 
print $pageStyle;

$Connect = mysql_connect($DB_SERVER, $user, $password) or die ("Cant connect to MySQL at $DB_SERVER");
mysql_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");

$toggle=0;

$order=$_GET["order"];
if (isset($_SESSION["accesslevel"])) {
	$level=$_SESSION["accesslevel"];
} else {
	$level=0;
}

$sql_query="SELECT * FROM measurementpoints";
if($order!=""){
	$sql_query=$sql_query . " ORDER BY $order";
}	

$result=mysql_query ($sql_query);
if(!$result) {
	print "sq: $sql_q <br>\n";
	print "Mysql Problems: " . mysql_error() . "<br>\n";
}

if(mysql_num_rows($result)>0) {
	print "<table border=0>";

	while($row = mysql_fetch_array($result)) {
		$noEthers=0;
		if($toggle==0) {
			$color="CCCCCC";
			$toggle=1;
		} else {
			$color="DDDDDD";
			$toggle=0;
		}	

		print 	"<tr bgcolor='$color' >";
	
	print "<tr><td colspan=2><h3>" . $row["name"] . "</h3><td colspan=2>";
	
	if(strlen($row["MAMPid"])>0) {
		$sql2="SELECT * FROM " . $row["MAMPid"] ."_filterlist";
		$result2=mysql_query($sql2);
		if(!$result2){
			print " MySQL pr. " . mysql_error() ."</td>";
		} else {
			if(mysql_num_rows($result2)>0) {
				print "Capturing " . mysql_num_rows($result2) . " filters </td>";
			} else {
				print "Idle</td>";
			}
		}
	} else {
		print "Not Auth</td>";
	}
	print "</tr>\n";

	print	"<tr bgcolor='$color'><td>" . $row["ip"] . "</td><td>". $row["port"] . "</td>";
	print 	"<td>". $row["comment"] . "</td><td>". $row["maxFilters"] ."</td></tr>\n";
	
	
	$sql2="SELECT * FROM " . $row["MAMPid"] ."_CIload";// ORDER BY id DESC LIMIT 0, 366";
	$result2=mysql_query($sql2);
	if(!$result2){
		print " MySQL pr. " . mysql_error() ."</td>";
	} else {
		$first=0;
		$count=0;
		$lastTime="";
		$numRows=mysql_num_rows($result2);
		if(mysql_num_rows($result2)>0) {
			while($row=mysql_fetch_array($result2)) {
			   if(($numRows-363)<$first){
			   	$arrFilters[$count]=$row["noFilters"];
			   	$arrMatchedPkts[$count]=$row["matchedPkts"];
			   	$arrPKT0[$count]=$row["PKT0"];
				$usageBU[$count]=$row["BU0"];
			   	$lastTime=$row["time"];
			   	$count++;
			   }
			   $first++;
			}
			$maxFilt=0;
			$maxMPpkts=0;
			$maxPkts=0;
			$time="";
			$maxBU=0;
			$first=$count;
			for($i=1;$i<$first;$i++){
			   $dnoFilters[($i-1)]=$arrFilters[$i];
			   if($dnoFilters[($i-1)]>$maxFilt) {
			   	$maxFilt=$dnoFilters[($i-1)];
			   }
			   $dmatchedPkts[($i-1)]=$arrMatchedPkts[$i]-$arrMatchedPkts[($i-1)];
			   if($dmatchedPkts[($i-1)]>$maxMPpkts) {
			   	$maxMPpkts=$dmatchedPkts[($i-1)];
			   }
			   $dPKT0[($i-1)]=$arrPKT0[$i]-$arrPKT0[($i-1)];
			   if($dPKT0[($i-1)]>$maxPkts) {
			   	$maxPkts=$dPKT0[($i-1)];
			   }
			   if($usageBU[$i]>$maxBU){
			     $maxBU=$usageBU[$i];
			   }

			}
//			print_r($dmatchedPkts);


			$lastMatch=$dmatchedPkts[($i-1)]/60;
			$lastPKT0=$dPKT0[($i-1)]/60;
			$lastBU0=$usageBU[($i-1)]/60;			
			
			$first=0;
			if($dnoFilters==0 || $maxFilt==0) {
				$noFilters=0;
			} else {
				$noFilters=$dnoFilters[0]/$maxFilt;
			}
			if($dmatchedPkts[0]==0 || $maxMPpkts==0){
				$matchedPkts=0;
			} else {
				$matchedPkts=$dmatchedPkts[0]/$maxMPpkts;
			}
			if($dPKT0[0]==0 || $maxPkts==0){
				$PKT0=0;
			} else {
				$PKT0=$dPKT0[0]/$maxPkts;
			}
			if($usageBU[0]==0 || $maxBU==0){
				$maxB0=0;
			} else {
				$maxB0=$usageBU[0]/$maxBU;
			}

			$time="0";
			for($i=1;$i<count($dnoFilters);$i++){
			 if($dnoFilters[$i]==0 || $maxFilt==0) {
			 	$noFilters=$noFilters . "," . 0;
			 } else {
			 	$noFilters=$noFilters . ",". $dnoFilters[$i]/$maxFilt;
			 }
			 if($dmatchedPkts[$i]==0  || $maxMPpkts==0) {
			  $matchedPkts=$matchedPkts . "," . 0;
			 } else {
			  $matchedPkts=$matchedPkts . "," . $dmatchedPkts[$i]/$maxMPpkts;
			 }
			 if($dPKT0[$i]==0  || $maxPkts==0) {
			  $PKT0=$PKT0 . "," . 0;
			 } else {
			  $PKT0=$PKT0 . ",". $dPKT0[$i]/$maxPkts;
			 }
			 if($usageBU[$i]==0 || $maxBU==0){
	                  $maxB0=$maxB0 . "," . 0;
			 } else {
                          $maxB0=$maxB0 . "," . $usageBU[$i]/$maxBU;
			 }

			 $time=$time ."," . $i;
			}			  
			
			$LEVELS=array('noFilters','matchedPkts','PKT0');
?>
			<tr><td colspan=5>
			<table>
			<tr><td><h3>Matched Packets</h3></td><td><h3>Capture Interface 0 Packets</h3></td><td><h3>Buffer Utilization</h3></td></tr>
			<tr><td><h3>Last heard from <? print "$lastTime (local time)"; ?></h3></td><td></td></tr>
			<tr>
			<td><a href="drawUsage.php?y=<? print $matchedPkts;?>&x=<? print $time;?>">
			<img src="drawUsage.php?y=<? print $matchedPkts;?>&x=<? print $time;?>&zoom=1"></a></td>
			<td><a href="drawUsage.php?y=<? print $PKT0;?>&x=<? print $time;?>">
			<img src="drawUsage.php?y=<? print $PKT0;?>&x=<? print $time;?>&zoom=1"></a></td>
			<td><a href="drawUsage.php?y=<? print $maxB0;?>&x=<? print $time;?>">
			<img src="drawUsage.php?y=<? print $maxB0;?>&x=<? print $time;?>&zoom=1"></a></td>
			</tr>
			<tr><td><? print "Max Matched/s : " . $maxMPpkts/60; ?></td>
			    <td><? print "Max Pkt/s : " . $maxPkts/60; ?>
			    <td><? print "Max Util/s : " . $maxBU/60; ?>
			</td></tr>

			<tr><td><? print "Last Matched/s : " . ($arrMatchedPkts[($count-1)]-$arrMatchedPkts[($count-2)])/60; ?></td>
			    <td><? print "Last Pkt/s : " . ($arrPKT0[($count-1)]-$arrPKT0[($count-2)])/60; ?>
			    <td><? print "Last Util/s : " . $lastBU0; ?>
			</td></tr>

			</table>
			</td></tr>
<?
				
		} else {
			print "Idle</td>";
		}
	}
	

	}
	print "</table>";
} else {
	print "No files yet.";
	exit;
}


?>
</body></html>