<?php
require("sessionCheck.php");
require("config.php");
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

<form action="addFilter2.php?SID=<? print $sid;?>&ID=<? print $ID; ?>" method="POST" name=myForm target="view">
<table BORDER >


<tr><td colspan=6>Filter Specification (DO NOT EDIT INDEX!!!)(If form handles it for you, else you do the math..)</td></tr>
<tr><td bgcolor = D3DCE3><div align=right>INDEX</div></td><td><input name="index" type="text" size="14" maxlength="14" value=0></td></tr>
<tr><td bgcolor = D3DCE3><div align=right>FILTER ID</div></td><td><input name="filter_id" type="text" size="14" maxlength="14" ></td></tr>
<tr><td colspan=6>Packet Specification</td></tr>
<tr><td bgcolor = D3DCE3><input name=cicb type=checkbox onChange="updateIndex();">512</td><td><div align=right>CI</div></td><td><input name="ci" type="text" size="8" maxlength="8" value='null'></td></tr>
<tr><td bgcolor = D3DCE3><input name=vlancb type=checkbox onChange="updateIndex();">256</td><td><div align=right>VLAN_TCI</div></td><td><input name="vlan_tci" type="text" size="5" maxlength="5" value=0></td><td bgcolor = D3DCE3><div align=right>VLAN_TCI_MASK</div></td><td><input name="vlan_tci_mask" type="text" size="14" maxlength="14" value=0></td><td><select name=vlanmask size=1 onChange="updateVLANmask();"><option value="ffff">ffff</option><option value="ff00">ff00</option><option value="00ff">00ff</option><option selected value="">Other</option></select></td></tr>
<tr><td bgcolor = D3DCE3><input name=ethtcb type=checkbox onChange="updateIndex();">128</td><td><div align=right>ETH_TYPE</div></td><td><input name="eth_type" type="text" size="5" maxlength="5" value=0></td><td bgcolor = D3DCE3><div align=right>ETH_TYPE_MASK</div></td><td><input name="eth_type_mask" type="text" size="14" maxlength="14" value=0></td><td><select name=ethmask size=1 onChange="updateETHmask();"><option value="ffff">ffff</option><option value="ff00">ff00</option><option value="00ff">00ff</option><option  selected value="">Other</option></select></td></tr>
<tr><td bgcolor = D3DCE3><input name=ethscb type=checkbox onChange="updateIndex();">64</td><td><div align=right>ETH_SRC</div></td><td><input name="eth_src" type="text" size="17" maxlength="17" value=000000000000></td><td bgcolor = D3DCE3><div align=right>ETH_SRC_MASK</div></td><td><input name="eth_src_mask" type="text" size="17" maxlength="17" value="000000000000"></td><td><select name=ethsrcmask size=1 onChange="updateETHSmask();"><option value="ffffffffffff">ffffffffffff</option><option value="000000000000">000000000000</option><option  selected value="">Other</option></select></td></tr>
<tr><td bgcolor = D3DCE3><input name=ethdcb type=checkbox onChange="updateIndex();">32</td><td><div align=right>ETH_DST</div></td><td><input name="eth_dst" type="text" size="17" maxlength="17" value=000000000000></td><td bgcolor = D3DCE3><div align=right>ETH_DST_MASK</div></td><td><input name="eth_dst_mask" type="text" size="17" maxlength="17" value="000000000000"></td><td><select name=ethdstmask size=1 onChange="updateETHDmask();"><option value="ffffffffffff">ffffffffffff</option><option value="000000000000">000000000000</option><option  selected value="">Other</option></select></td></tr>
<tr><td bgcolor = D3DCE3><input name=ippcb type=checkbox onChange="updateIndex();">16</td><td><div align=right>IP_PROTO</div></td><td><input name="ip_proto" type="text" size="5" maxlength="5" value=0></td><td><select name=ipproto_predef size=1 onChange="updateIPproto();"><option value="17">UDP</option><option value="6">TCP</option><option value="1">ICMP</option><option  selected value="">Other</option></select></td></tr>
<tr><td bgcolor = D3DCE3><input name=ipscb type=checkbox onChange="updateIndex();">8</td><td><div align=right>IP_SRC</div></td><td><input name="ip_src" type="text" size="16" maxlength="16" value=0></td><td bgcolor = D3DCE3><div align=right>IP_SRC_MASK</div></td><td><input name="ip_src_mask" type="text" size="16" maxlength="16" value=0></td><td><select name=ipsmask size=1 onChange="updateIPSmask();"><option value="255.255.255.255">255.255.255.255</option><option value="255.255.255.0">255.255.255.0</option><option  selected value="">Other</option></select></td></tr>
<tr><td bgcolor = D3DCE3><input name=ipdcb type=checkbox onChange="updateIndex();">4</td><td><div align=right>IP_DST</div></td><td><input name="ip_dst" type="text" size="16" maxlength="16" value=0></td><td bgcolor = D3DCE3><div align=right>IP_DST_MASK</div></td><td><input name="ip_dst_mask" type="text" size="16" maxlength="16" value=0></td><td><select name=ipdmask size=1 onChange="updateIPDmask();"><option value="255.255.255.255">255.255.255.255</option><option value="255.255.255.0">255.255.255.0</option><option  selected value="">Other</option></select></td></tr>
<tr><td bgcolor = D3DCE3><input name=sprtcb type=checkbox onChange="updateIndex();">2</td><td><div align=right>SRC_PORT</div></td><td><input name="src_port" type="text" size="5" maxlength="5" value=0></td><td bgcolor = D3DCE3><div align=right>SRC_PORT_MASK</div></td><td><input name="src_port_mask" type="text" size="5" maxlength="5" value=0></td><td><select name=portsmask size=1 onChange="updatePORTSmask();"><option value="65535">ffff</option><option value="0">0</option><option  selected value="">Other</option></select></td></tr>
<tr><td bgcolor = D3DCE3><input name=dprtcb type=checkbox onChange="updateIndex();">1</td><td><div align=right>DST_PORT</div></td><td><input name="dst_port" type="text" size="5" maxlength="5" value=0></td><td bgcolor = D3DCE3><div align=right>DST_PORT_MASK</div></td><td><input name="dst_port_mask" type="text" size="5" maxlength="5" value=0></td><td><select name=portdmask size=1 onChange="updatePORTDmask();"><option value="65535">ffff</option><option value="0">0</option><option  selected value="">Other</option></select></td></tr>
<tr><td bgcolor=D3DcE3></td><td><div align=right>DESTADDR</div></td><td><input name="destaddr" type="text" size="23" maxlength="23" value="010000000000"></td><td bgcolor=d3dce3>TYPE</td><td><input name="stream_type" type="text" size="5" maxlength="5" value=1></td><td><select name=type size=1 onChange="updateType();">
<option value="0">File</option>
<option value="1" selected>Ethernet Multicast</option>
<option value="2">UDP</option>
<option value="3">TCP</option></select>Note: TCP requires a running TCP consumer</td></tr>
<tr><td bgcolor=d3dce3></td><td><div align=right>CAPLEN</div></td><td><input name="caplen" type="text" size="14" maxlength="4" value="54"></td></tr>
<tr><td colspan=6>MP Receiving Filter</td></tr>
<?
$Connect = mysql_connect($DB_SERVER, $user, $password) or die ("Cant connect to MySQL at $DB_SERVER");
mysql_select_db($DATABASE,$Connect) or die ("Cant connect to $DATABASE database");

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
		if($first==0) {
			print " checked ";
			$first=1;
		}
		print "></td><td>". $row["name"] ."</td><td colspan=3>". $row["comment"] . "</td><td>". $row["maxFilters"] ."</td></tr>";
	}
} else {
	print "No MPs avail.";
}

?>
<tr><td colspan=3><input type="submit" value="Add Filter"></td><td colspan=3><input type="reset" value="Reset"></td></td>
</table>
</form>
</body>
</html>
