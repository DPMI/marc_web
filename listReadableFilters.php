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
	 print "</tr>\n";
	 print "<tr bgcolor=dddddd><th>Filter_ID</th>";
	 print "<th colspan=12>Filter Description</th>";
	 print "<th>Consumer Information</th>";	
	 print "</tr>\n";

	
	 $tabel=$row["MAMPid"]."_filterlist";
	 $query2="SELECT * FROM $tabel ORDER BY filter_id";
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
/*
	  print "<td>".$row2["filter_id"] . "</td>";
	  print "<td>".$row2["ind"] . "</td>";
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
//	  print "<td><a href='editFilter.php?SID=$sid&filter_id=".$row2["filter_id"] ."&MAMPid=".$row["MAMPid"] ."'><img width=12 height=13  border=0 ALT='Edit' src='button_edit.png'></a>";
//	  print "<a href='delFilter.php?SID=$sid&filter_id=".$row2["filter_id"] ."&MAMPid=".$row["MAMPid"] ."'><img width=12 height=13  border=0 ALT='Drop' src='button_drop.png'></a>";
//	  print "<a href='verifyFilter.php?SID=$sid&filter_id=".$row2["filter_id"] ."&MAMPid=".$row["MAMPid"] ."'><img width=12 height=13  border=0 ALT='Verify' src='button_properties.png'></a>";
//	  print "</td>";
	  print "<tr>\n";
*/
	  print "<tr bgcolor='$color'>";
	  print "<td>".$row2["filter_id"] . "</td>";
	  print "<td colspan=12>";
	  $index=$row2["ind"];
	  $theString="($index)";
	  $initial=1;

	
	  if($index&512){
		if ($initial==0) {
		  $theString="(if = " . $row2["CI_ID"] . ") ";
		  $initial=1;
		} else {
		  $theString="$theString and (if = " . $row2["CI_ID"] . ") ";
		}
	  }
	  if($index&256){
		if ($initial==0) {
		  $theString="(vlantci = " . $row2["VLAN_TCI"] . ") ";
		  $initial=1;
		} else {
		  $theString="$theString and (vlantci = " . $row2["VLAN_TCI"] . ") ";
		}
	  }
	  if($index&128){
		if ($initial==0) {
		  $theString="(ethtype = " . $row2["ETH_TYPE"] . ") ";
		  $initial=1;
		} else {
		  $theString="$theString and (ethtype = " . $row2["ETH_TYPE"] . ") ";
		}
	  }
	  if($index&64){
		if ($initial==0) {
		  $theString="(ethsrc = " . $row2["ETH_SRC"] . ") ";
		  $initial=1;
		} else {
		  $theString="$theString and (ethsrc = " . $row2["ETH_SRC"] . ") ";
		}
	  }
	  if($index&32){
		if ($initial==0) {
		  $theString="(ethdst = " . $row2["ETH_DST"] . ") ";
		  $initial=1;
		} else {
		  $theString="$theString and (ethdst = " . $row2["ETH_DST"] . ") ";
		}
	  }
	  if($index&16){
		if ($initial==0) {
		  $theString="(IP PROTO = " . getprotobynumber($row2["IP_PROTO"]) . ") ";
		  $initial=1;
		} else {
		  $theString="$theString and (IP PROTO = " . getprotobynumber($row2["IP_PROTO"]) . ") ";
		}
	  }
	  if($index&8){
		if ($initial==0) {
		  $theString="(IP SRC = " . $row2["IP_SRC"] . ") ";
		  $initial=1;
		} else {
		  $theString="$theString and (IP SRC = " . $row2["IP_SRC"] . ") ";
		}
	  }
	  if($index&4){
		if ($initial==0) {
		  $theString="(IP DST = " . $row2["IP_DST"] . ") ";
		  $initial=1;
		} else {
		  $theString="$theString and (IP DST = " . $row2["IP_DST"] . ") ";
		}
	  }
	  if($index&2){
		if ($initial==0) {
		  $theString="(SPORT= " . $row2["SRC_PORT"] . ") ";
		  $initial=1;
		} else {
		  $theString="$theString and (SPORT = " . $row2["SRC_PORT"] . ") ";
		}
	  }
	  if($index&1){
		if ($initial==0) {
		  $theString="(DPORT= " . $row2["DST_PORT"] . ") ";
		  $initial=1;
		} else {
		  $theString="$theString and (DPORT = " . $row2["DST_PORT"] . ") ";
		}
	  }
	  $readable="\{ $theString } </td><td> ";
	  if($row2["TYPE"]==0) {
	  	$readable = $readable . "Local to";
	  } 
	  if($row2["TYPE"]==1) {
	  	$readable = $readable . "Ethernet to 0x";
	  } 
	  if($row2["TYPE"]==2) {
	  	$readable = $readable . "UDP to ";
	  } 
	  if($row2["TYPE"]==3) {
	  	$readable = $readable . "TCP to ";
	  } 
	
	  $readable = $readable . "(" . $row2["DESTADDR"] . ") length " . $row2["CAPLEN"] . " bytes."; 

	  print "$readable";
	  print "</td></tr>";
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