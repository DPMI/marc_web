<?
require("sessionCheck.php");
require("config.inc");

$sid=$_GET["SID"];
$nSid=session_id();
if($sid!=$nSid) {
	print "The passes SID is not equal to the one found here.. problems!";
//	print "$sid == $nSid <br>\n";
		exit();
}


$FILTER_ID=$_GET["filter_id"];
$MAMPid=$_GET["MAMPid"];

$Connect = mysql_connect($DB_SERVER, $user, $password) or die ("Cant connect to MySQL at $DB_SERVER");
mysql_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");

$toggle=0;


if (isset($_SESSION["accesslevel"])) {
	$level=$_SESSION["accesslevel"];
} else {
	$level=0;
}
$tabel=$MAMPid."_filterlist";
$query2="SELECT * FROM $tabel WHERE filter_id='$FILTER_ID'";
$result2=mysql_query($query2);
if(!$result2) {
	print "sq: $query2 Mysql Problems: " . mysql_error() . "\n";
	return;
} else {
   if(mysql_num_rows($result2)==0) {
	print "No filter by that name.\n";
	return;
   }
}
$row = mysql_fetch_array($result2);

?>
<SCRIPT LANGUAGE="JavaScript">
function updateIPproto() {
	document.myForm.ip_proto.value=document.myForm.ipproto_predef.value;
	return;
}

function updateIndex() {
	ci=0;
	vlan=0;
	ethtype=0;
	ethsrc=0;
	ethdst=0;
	ipprot=0;
	ipsrc=0;
	ipdst=0;
	srcport=0;
	dstport=0;
	if(document.myForm.cicb.checked==1){ ci=512;}
	if(document.myForm.vlancb.checked==1) { vlan=256;}
	if(document.myForm.ethtcb.checked==1) { ethtype=128;}
	if(document.myForm.ethscb.checked==1) { ethsrc=64; }
	if(document.myForm.ethdcb.checked==1) { ethdst=32; }
	if(document.myForm.ippcb.checked==1) { ipprot=16; }
	if(document.myForm.ipscb.checked==1) { ipsrc=8; }
	if(document.myForm.ipdcb.checked==1) { ipdst=4; }
	if(document.myForm.sprtcb.checked==1) { srcport=2; }
	if(document.myForm.dprtcb.checked==1) { dstport=1; }
	document.myForm.index.value=ci+vlan+ethtype+ethsrc+ethdst+ipprot+ipsrc+ipdst+srcport+dstport;
	return;
}

function updateVLANmask(){
	document.myForm.vlan_tci_mask.value=document.myForm.vlanmask.value;
	return;
}
function updateETHmask(){
	document.myForm.eth_type_mask.value=document.myForm.ethmask.value;
	return;
}
function updateETHSmask(){
	document.myForm.eth_src_mask.value=document.myForm.ethsrcmask.value;
	return;
}
function updateETHDmask(){
	document.myForm.eth_dst_mask.value=document.myForm.ethdstmask.value;
	return;
}


function updateIPSmask(){
	document.myForm.ip_src_mask.value=document.myForm.ipsmask.value;
	return;
}
function updateIPDmask(){
	document.myForm.ip_dst_mask.value=document.myForm.ipdmask.value;
	return;
}
function updatePORTSmask(){
	document.myForm.src_port_mask.value=document.myForm.portsmask.value;
	return;
}
function updatePORTDmask(){
	document.myForm.dst_port_mask.value=document.myForm.portdmask.value;
	return;
}
function updateType(){
	document.myForm.stream_type.value=document.myForm.type.value;
	return;
}

</SCRIPT>
<html>
<head>
  <title>Add Filter</title>
  <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
</head>
<? 
print $pageStyle;


?>

<form action="editFilter2.php?SID=<? print $sid;?>&ID=<? print $ID; ?>" method="POST" name=myForm target="view">
<input type=hidden name=old_filter_id value=<? print $row["filter_id"]; ?>>
<table BORDER >
<tr><td colspan=6>Filter Specification (DO NOT EDIT INDEX!!!)</td></tr>
<tr><td bgcolor = D3DCE3><div align=right>INDEX</div></td><td><input name="index" type="text" size="14" maxlength="14" value=<? print $row["ind"]; ?>></td></tr>
<tr><td bgcolor = D3DCE3><div align=right>FILTER ID</div></td><td><input name="filter_id" type="text" size="14" maxlength="14" value=<? print $row["filter_id"]; ?>></td></tr>
<tr><td colspan=6>Packet Specification</td></tr>
<tr><td bgcolor = D3DCE3><input name=cicb type=checkbox onChange="updateIndex();"  <?if($row["ind"]&512){print ' checked ';}?> >512</td><td><div align=right>CI</div></td>		<td><input name="ci" type="text" size="8" maxlength="8" value=<? print $row["CI_ID"]; ?> ></td></tr>
<tr><td bgcolor = D3DCE3><input name=vlancb type=checkbox onChange="updateIndex();"<?if($row["ind"]&256){print ' checked ';}?> >256</td><td><div align=right>VLAN_TCI</div></td>	<td><input name="vlan_tci" type="text" size="5" maxlength="5" value=<? print $row["VLAN_TCI"]; ?> ></td><td bgcolor = D3DCE3><div align=right>VLAN_TCI_MASK</div></td><td><input name="vlan_tci_mask" type="text" size="14" maxlength="14" value=<? print $row["VLAN_TCI_MASK"]; ?> ></td><td><select name=vlanmask size=1 onChange="updateVLANmask();"><option value="65535">ffff</option><option value="ff00">ff00</option><option value="00ff">00ff</option><option selected value="">Other</option></select></td></tr>
<tr><td bgcolor = D3DCE3><input name=ethtcb type=checkbox onChange="updateIndex();"<?if($row["ind"]&128){print ' checked ';}?> >128</td><td><div align=right>ETH_TYPE</div></td>	<td><input name="eth_type" type="text" size="5" maxlength="5" value=<? print $row["ETH_TYPE"]; ?> ></td><td bgcolor = D3DCE3><div align=right>ETH_TYPE_MASK</div></td><td><input name="eth_type_mask" type="text" size="14" maxlength="14" value=<? print $row["ETH_TYPE_MASK"]; ?> ></td><td><select name=ethmask size=1 onChange="updateETHmask();"><option value="65535">ffff</option><option value="ff00">ff00</option><option value="00ff">00ff</option><option  selected value="">Other</option></select></td></tr>
<tr><td bgcolor = D3DCE3><input name=ethscb type=checkbox onChange="updateIndex();"<?if($row["ind"]&64){print ' checked ';}?> >64</td><td><div align=right>ETH_SRC</div></td>	<td><input name="eth_src" type="text" size="17" maxlength="17" value=<? print $row["ETH_SRC"]; ?> ></td><td bgcolor = D3DCE3><div align=right>ETH_SRC_MASK</div></td><td><input name="eth_src_mask" type="text" size="17" maxlength="17" value=<? print $row["ETH_SRC_MASK"]; ?> ></td><td><select name=ethsrcmask size=1 onChange="updateETHSmask();"><option value="ffffffffffff">ffffffffffff</option><option value="000000000000">000000000000</option><option  selected value="">Other</option></select></td></tr>
<tr><td bgcolor = D3DCE3><input name=ethdcb type=checkbox onChange="updateIndex();"<?if($row["ind"]&32){print ' checked ';}?> >32</td><td><div align=right>ETH_DST</div></td>	<td><input name="eth_dst" type="text" size="17" maxlength="17" value=<? print $row["ETH_DST"]; ?> ></td><td bgcolor = D3DCE3><div align=right>ETH_DST_MASK</div></td><td><input name="eth_dst_mask" type="text" size="17" maxlength="17" value=<? print $row["ETH_DST_MASK"]; ?> ></td><td><select name=ethdstmask size=1 onChange="updateETHDmask();"><option value="ffffffffffff">ffffffffffff</option><option value="000000000000">000000000000</option><option  selected value="">Other</option></select></td></tr>
<tr><td bgcolor = D3DCE3><input name=ippcb type=checkbox onChange="updateIndex();" <?if($row["ind"]&16){print ' checked ';}?> >16</td><td><div align=right>IP_PROTO</div></td>	<td><input name="ip_proto" type="text" size="5" maxlength="5" value=<? print $row["IP_PROTO"]; ?> ></td><td><select name=ipproto_predef size=1 onChange="updateIPproto();"><option <? if($row["IP_PROTO"]==17) print "selected "; ?>value="17">UDP</option><option <? if($row["IP_PROTO"]==6) print "selected "; ?>value="6" >TCP</option><option <? if($row["IP_PROTO"]==1) print "selected "; ?>value="1">ICMP</option><option value="">Other</option></select></td></tr>
<tr><td bgcolor = D3DCE3><input name=ipscb type=checkbox onChange="updateIndex();" <?if($row["ind"]&8){print ' checked ';}?> >8</td><td><div align=right>IP_SRC</div></td>		<td><input name="ip_src" type="text" size="16" maxlength="16" value=<? print $row["IP_SRC"]; ?> ></td><td bgcolor = D3DCE3><div align=right>IP_SRC_MASK</div></td><td><input name="ip_src_mask" type="text" size="16" maxlength="16" value=<? print $row["IP_SRC_MASK"]; ?> ></td><td><select name=ipsmask size=1 onChange="updateIPSmask();"><option value="255.255.255.255">255.255.255.255</option><option value="255.255.255.0">255.255.255.0</option><option  selected value="">Other</option></select></td></tr>
<tr><td bgcolor = D3DCE3><input name=ipdcb type=checkbox onChange="updateIndex();" <?if($row["ind"]&4){print ' checked ';}?> >4</td><td><div align=right>IP_DST</div></td>		<td><input name="ip_dst" type="text" size="16" maxlength="16" value=<? print $row["IP_DST"]; ?> ></td><td bgcolor = D3DCE3><div align=right>IP_DST_MASK</div></td><td><input name="ip_dst_mask" type="text" size="16" maxlength="16" value=<? print $row["IP_DST_MASK"]; ?> ></td><td><select name=ipdmask size=1 onChange="updateIPDmask();"><option value="255.255.255.255">255.255.255.255</option><option value="255.255.255.0">255.255.255.0</option><option  selected value="">Other</option></select></td></tr>
<tr><td bgcolor = D3DCE3><input name=sprtcb type=checkbox onChange="updateIndex();"<?if($row["ind"]&2){print ' checked ';}?> >2</td><td><div align=right>SRC_PORT</div></td>	<td><input name="src_port" type="text" size="5" maxlength="5" value=<? print $row["SRC_PORT"]; ?> ></td><td bgcolor = D3DCE3><div align=right>SRC_PORT_MASK</div></td><td><input name="src_port_mask" type="text" size="5" maxlength="5" value=<? print $row["SRC_PORT_MASK"]; ?> ></td><td><select name=portsmask size=1 onChange="updatePORTSmask();"><option value="65535">ffff</option><option value="0">0</option><option  selected value="">Other</option></select></td></tr>
<tr><td bgcolor = D3DCE3><input name=dprtcb type=checkbox onChange="updateIndex();"<?if($row["ind"]&1){print ' checked ';}?> >1</td><td><div align=right>DST_PORT</div></td>	<td><input name="dst_port" type="text" size="5" maxlength="5" value=<? print $row["DST_PORT"]; ?> ></td><td bgcolor = D3DCE3><div align=right>DST_PORT_MASK</div></td><td><input name="dst_port_mask" type="text" size="5" maxlength="5" value=<? print $row["DST_PORT_MASK"]; ?> ></td><td><select name=portdmask size=1 onChange="updatePORTDmask();"><option value="65535">ffff</option><option value="0">0</option><option  selected value="">Other</option></select></td></tr>
<tr><td bgcolor = D3DCE3><div align=right>DESTADDR</div></td><td><input name="destaddr" type="text" size="23" maxlength="23" value=<? print $row["DESTADDR"]; ?>></td><td bgcolor=d3dce3>TYPE</td>
<td><input name="stream_type" type="text" size="5" maxlength="5" value=<? print $row["TYPE"]; ?>></td><td>
<select name=type size=1 onChange="updateType();" >
<option value="0" <? if($row["TYPE"]==0){print " selected ";} ?>>File</option>
<option value="1" <? if($row["TYPE"]==1){print " selected ";} ?>>Ethernet Multicast</option>
<option value="2" <? if($row["TYPE"]==2){print " selected ";} ?>>UDP</option>
<option value="3" <? if($row["TYPE"]==3){print " selected ";} ?>>TCP</option>
</select>Note: TCP requires a running TCP consumer</td></tr>
<tr><td bgcolor=d3dce3></td><td><div align=right>CAPLEN</div></td><td><input name="caplen" type="text" size="14" maxlength="4" value=<? print $row["CAPLEN"]; ?>></td></tr>
</tr>
<tr><td colspan=6>MP Receiving Filter</td></tr>
<tr><td colspan=6>DO NOT CHANGE THIS IN EDIT MODE!!!!<br>DELETE OLD RULE AND MAKE A NEW!!!!!</td></tr>

<?
$sql_query="SELECT * FROM measurementpoints";
$result=mysql_query ($sql_query);
if(!$result) {
	print "sq: $sql_q <br>\n";
	print "Mysql Problems: " . mysql_error() . "<br>\n";
	return;
}

if(mysql_num_rows($result)>0) {
	print "<tr><th></th>";
	print "<th><b>Name</b></th>";
	print "<th colspan = 3><b>Comment</b></th>";
	print "<th><b>Max filters</b></th></tr>";
	$toggle=0;
	$first=0;
	while($row = mysql_fetch_array($result)) {
		$noEthers=0;
		if($toggle==0) {
			$color="CCCCCC";
			$toggle=1;
		} else {
			$color="DDDDDD";
			$toggle=0;
		}	

		print 	"<tr bgcolor='$color' ><td><input type=radio name=mp value=". $row["MAMPid"];
		if(strcmp($row["MAMPid"],$MAMPid)==0){
			print " checked ";
		}
		print "></td><td>". $row["name"] ."</td><td colspan=3>". $row["comment"] . "</td><td>". $row["maxFilters"] ."</td></tr>";
	}
} else {
	print "No MPs avail.";
}

?>
<tr><td colspan=3><input type="submit" value="Update Filter"></td><td colspan=3><input type="reset" value="Reset"></td></td>
</table>
</form>
</body>
</html>
