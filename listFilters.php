<?
require("sessionCheck.php");
require("config.inc");


$sid=$_GET["SID"];
if (isset($sid)) {
//	session_start();
	$nSid=session_id();
	if($sid!=$nSid) {
		print "The passes SID is not equal to the one found here.. problems!";
//		print "$sid == $nSid <br>\n";
			exit();
	}
} 

?>
<html>
<? 
print $pageStyle;

$Connect = mysql_connect($DB_SERVER, $user, $password) or die ("Cant connect to MySQL at $DB_SERVER");
mysql_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");

$toggle=0;


if (isset($_SESSION["accesslevel"])) {
	$level=$_SESSION["accesslevel"];
} else {
	$level=0;
}

$sql_query="SELECT * FROM measurementpoints";

$result=mysql_query ($sql_query);
if(!$result) {
	print "sq: $sql_query <br>\n";
	print "Mysql Problems: " . mysql_error() . "<br>\n";
}

if(mysql_num_rows($result)>0) {
	print "<table border=1>";

	while($row = mysql_fetch_array($result)){
 	 print "<tr bgcolor=eeeeee><th colspan = 14 ><b>".$row["name"] . "</b></th>";
  	 print "<th>";
  	 print "<a href='verifyFilters.php?SID=$sid&MAMPid=".$row["MAMPid"] ."'><img width=12 height=13  border=0 ALT='Verify all filters' src='button_properties.png'></a>";
  	 print "<a href='addFilter.php?SID=$sid'><img width=12 height=13  border=0 ALT='Add filter' src='button_insert.png'></a>";
	 print "</th></tr>\n";
	 print "<tr bgcolor=dddddd><th >Index</th><th>Filter_ID</th><th>CI</th><th>VLAN_TCI/<br>MASK</th>";
	 print "<th>ETH_TYPE/<br>MASK<th>ETH_SRC/<br>MASK</th><th>ETH_DST/<br>MASK</th>";
	 print "<th>IP_PROTO</th>";
	 print "<th>IP_SRC/<br>MASK</th><th>IP_DST/<br>MASK</th><th>SRC_PORT/<br>MASK</th><th>DST_PORT/<br>MASK</th>";
	 print "<th>DESTADDR/TYPE</th><th>CAPLEN</th><th></th></tr>\n";

	
	 $tabel=$row["MAMPid"]."_filterlist";
	 $query2="SELECT * FROM $tabel";
	 $result2=mysql_query($query2);
	 if(!$result2) {
		print "<tr><td colspan=14> sq: $query2 Mysql Problems: " . mysql_error() . "</td></tr>\n";
	 } else {
	   if(mysql_num_rows($result2)==0) {
	   	print "<tr><td colspan=14>No Filters</td></tr>\n";
	   } else {	
 	  while($row2 = mysql_fetch_array($result2)){
	  if($toggle==0) {
		$color="aaaaaa";
		$toggle=1;
	  } else {
		$color="bbbbbb";
		$toggle=0;
	  }	

 	  print "<tr bgcolor='$color' >";
	  print "<td>".$row2["ind"] . "</td>";
	  print "<td>".$row2["filter_id"] . "</td>";
  	  print "<td>".$row2["CI_ID"] . "</td>";
	  print "<td>".$row2["VLAN_TCI"] ."/<br>" .$row2["VLAN_TCI_MASK"]. "</td>";
	  print "<td>".$row2["ETH_TYPE"] ."/<br>" .$row2["ETH_TYPE_MASK"]. "</td>";
	  print "<td>".$row2["ETH_SRC"] ."/<br>" .$row2["ETH_SRC_MASK"]. "</td>";
	  print "<td>".$row2["ETH_DST"] ."/<br>" .$row2["ETH_DST_MASK"]. "</td>";
	  print "<td>".$row2["IP_PROTO"] . "</td>";
	  print "<td>".$row2["IP_SRC"] ."/<br>" .$row2["IP_SRC_MASK"]. "</td>";
	  print "<td>".$row2["IP_DST"] ."/<br>" .$row2["IP_DST_MASK"]. "</td>";
  	  print "<td>".$row2["SRC_PORT"] ."/<br>" .$row2["SRC_PORT_MASK"]. "</td>";
	  print "<td>".$row2["DST_PORT"] ."/<br>" .$row2["DST_PORT_MASK"]. "</td>";
	  print "<td>".$row2["DESTADDR"] . "/" .$row2["TYPE"] ."</td>";
	  print "<td>".$row2["CAPLEN"] . "</td>";
	  print "<td><a href='editFilter.php?SID=$sid&filter_id=".$row2["filter_id"] ."&MAMPid=".$row["MAMPid"] ."'><img width=12 height=13  border=0 ALT='Edit' src='button_edit.png'></a>";
	  print "<a href='delFilter.php?SID=$sid&filter_id=".$row2["filter_id"] ."&MAMPid=".$row["MAMPid"] ."'><img width=12 height=13  border=0 ALT='Drop' src='button_drop.png'></a>";
	  print "<a href='verifyFilter.php?SID=$sid&filter_id=".$row2["filter_id"] ."&MAMPid=".$row["MAMPid"] ."'><img width=12 height=13  border=0 ALT='Verify' src='button_properties.png'></a>";
	  print "</td>";
	  print "<tr>\n";
	 }
	}
	}	
	}
	print "</table>";
} else {
	print "No MPs found";
	exit;
}


?>
</body></html>