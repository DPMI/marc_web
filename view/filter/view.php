<?php //  -*- mode:html;  -*- ?>
<?php

/**
 * callback for array_map used by select.
 */
$build_options_default = null; /* @hack: this is set by select. */
function build_options($key, $value){
  global $build_options_default, $build_options_selected;
  $selected = '';
  if ( $value == $build_options_default ){
    $selected = ' selected="selected"';
  }
  return "<option value=\"$value\"$selected>$key</option>";
}

/**
 * Build a html select-box.
 * @param name Name of the selectionbox
 * @param values Associative array of all the options. Numerical indices (and
 *               thus non-assoc arrays) are converted to have the value as key.
 *               E.g. passing array('foo') is the same as array('foo' => 'foo')
 * @param default Array with default selected option. It is matched in order, so
 *                only the first match (value, not key) is selected.
 * @param update Name of another field to update (using javascript) when a value
 *               is selected.
 * @param extra Extra attributes to select, e.g. style.
 */
function select($name, array $values, array $default=null, $update=null, array $extra=null){
  $js = '';
  $extra_str = '';

  if ( $update != null ){
    $js = "onchange=\"document.myForm.$update.value = document.myForm.$name.value;\"";
  }

  /* @hack: numerical indices use the $value as key. this is only suitable for this use case. */
  $normalized = array();
  foreach ( $values as $key => $value ){
    if ( is_numeric($key) ){
      $key = $value;
    }
    $normalized[$key] = $value;
  }

  /* find default value */
  global $build_options_default, $build_options_selected;
  $build_options_default = null;
  $build_options_selected = false;
  if ( $default != null ){
    $tmp = array_values($normalized);
    foreach ( $default as $x ){
      foreach ( $tmp as $y ){
	/* literal match */
	$a = strcasecmp($x, $y) == 0;

	/* option is hex, value is dec */
	$b = is_numeric($x) && strcmp(substr($y,0,2), "0x") == 0 && hexdec($y) == (int)$x;

	/* option is empty string, value is 0 (because column is int), no literal match if 0 is not an option */
	$c = $y == '' && $x == '0';

	if ( $a || $b || $c ){
	  $build_options_default = $y;
	  break 2;
	}
      }
    }
  }

  /* build extra */
  if ( $extra != null ){
    foreach ( $extra as $key => $value ){
      $extra_str .= "$key=\"$value\" ";
    }
  }

  $head = "<select name=\"$name\" size=\"1\" $js $extra_str>";
  $foot = "</select>";
  $options = array_map('build_options', array_keys($normalized), array_values($normalized));

  return $head . implode('', $options) . $foot;
}

/* get all multicast addresses currently in use */
global $db;
$address = array();
$stmt = $db->prepare("SELECT mampid FROM measurementpoints");
$stmt->bind_result($mampid);
$stmt->execute();
$stmt->store_result();
while ( $stmt->fetch() ){
	$inner = $db->prepare("SELECT filter_id, DESTADDR FROM {$mampid}_filterlist WHERE TYPE=1 ORDER BY DESTADDR");
	if ( !$inner ){ die($db->error); };
	$inner->bind_result($id, $addr);
	$inner->execute();
	while ( $inner->fetch() ){
		$address[] = "{addr: '$addr', 'mampid': '$mampid', id: $id}";
	}
	$inner->close();
}
$stmt->close();

?>
<script type="text/javascript" />
	var ethernet_addr = [<?=implode(',', $address)?>];
	$(document).ready(function(){
		filter_init();
	});
</script>

<h1>
  <a href="<?=$index?>/MP">Measurement Points</a> &gt;
  <a href="<?=$index?>/MP/view/<?=$mp->MAMPid?>"><?=$mp->name?></a> &gt;
  <a href="<?=$index?>/MP/filter/<?=$mp->MAMPid?>">Filters</a> &gt;
<?php if ( $filter_exist ){ ?>
  Edit
<?php } else { ?>
  Add
<?php } ?>
</h1>
<div id="help">
	<span id="description">
		<h2>Description</h2>
		<p>Measurement points process filters in the order specified by filter ID with a lower ID having higher priority. Packets will be matched in order and is processed as soon as a match is found and not by subsequent filters.</p>
		<p>By selecting OR only one field must match but selecting AND requires all fields to match. Fields which isn't enabled will implicitly match. A filter without any enabled rows will always match.</p>
		<p>All fields with a mask will apply the mask both to the selected value and the packets value.</p>
		<p>By pressing "parse" all fields will be validated and converted to native format.</p>
	</span>
</div>
<div id="filter" class="form">
  <noscript>
    <p class="filter_notice">This page requires javascript to function properly!</p>
    <p class="filter_notice">You need to manually update index bitmask. Non-decimal values are NOT supported!</p>
  </noscript>
<?php foreach ( $errors as $msg ){ ?>
    <p class="filter_notice"><?=$msg?></p>
<?php } /* foreach $errors */ ?>

      <form action="<?=$index?>/MP/filter_update/<?=$mp->MAMPid?>" method="post" name="myForm" onsubmit="return filter_submit();">
<?php if ( $filter_exist ){ ?>
	<input type="hidden" name="old_filter_id" value="<?=$filter->filter_id?>" />
<?php } else {?>
	<input type="hidden" name="old_filter_id" value="-1" />
<?php } ?>

	<p>
	  INDEX     <input id="ind"       name="ind"       type="text" size="14" value="<?=$filter->ind?>" />
	  FILTER ID <input id="filter_id" name="filter_id" type="text" size="14" value="<?=$filter->filter_id?>" maxlength="14" />
	  <input type="radio" id="mode_and" name="mode" value="AND" <?=$filter->mode == 'AND' ? 'checked="checked"' : ''?>/><label for="mode_and">AND</label>
	  <input type="radio" id="mode_or"  name="mode" value="OR"  <?=$filter->mode == 'OR'  ? 'checked="checked"' : ''?>/><label for="mode_or">OR</label>
	</p>

	<h2>Filter specification</h2>
	<table border="0" cellspacing="0">

	  <tr class="row" data-description="<h2>Capture interface</h2><p><b>Format</b>: [a-zA-Z0-9]{1,8}</p><p>Matches capture interface by searching for string, e.g. &quot;d0&quot; will match both &quot;d00&quot; and &quot;d01&quot;.</p><p>Endace DAG cards use the format 'd' followed by device id and capture direction, e.g. &quot;d00&quot; indicating device dag0 in first direction.</p>">
		  <td style="width: 50px;">
			  <input name="ci_cb" id="cb[9]" data-index="9" type="checkbox" <?=$filter->ind&512 ? 'checked="checked"' : '' ?> />512
		  </td>
		  <td class="label" title="Capture Interface">
			  CI
		  </td>
		  <td colspan="4">
			  <input id="CL_ID" name="CI_ID" title="Capture Interface" type="text" size="8" maxlength="8" onchange="filter_clear(this);" value="<?=$filter->CI_ID?>" />
		  </td>
	  </tr>

	  <tr class="row" data-description="<h2>VLAN Tag Control Information</h2><p>Filter packets with the 2-byte VLAN tag.</p><p>If you intend to filter only on VLAN ID select correct mask first. To test for presence of VLAN tag you can use ETH_TYPE instead.</p>">
		  <td style="width: 50px;">
			  <input name="vlan_cb" id="cb[8]" data-index="8" type="checkbox" <?=$filter->ind&256 ? 'checked="checked"' : '' ?> />256
		  </td>
		  <td class="label" title="VLAN Tag Control Information"  >
			  VLAN TCI
		  </td>
		  <td colspan="1">
			  <input id="VLAN_TCI" name="VLAN_TCI" type="text" size="5"  maxlength="5"  onchange="filter_clear(this);" value="<?=$filter->VLAN_TCI ?>" /></td>
		  <td class="label">
			  VLAN TCI mask
		  </td>
		  <td>
			  <input id="VLAN_TCI_MASK" name="VLAN_TCI_MASK" type="text" size="14" maxlength="14" value="<?=$filter->VLAN_TCI_MASK?>" />
		  </td>
		  <td>
			  <?=select('vlanmask_selection',   array('' => '0xffff', 'User priority' => '0xe000', 'CFI' => '0x1000', 'VLAN ID' => '0x0fff', 'Other' => ''), array($filter->VLAN_TCI_MASK, ''), 'VLAN_TCI_MASK')?>
		  </td>
	  </tr>

	  <tr class="row" data-description="<h2>Ethernet encapsulated protocol</h2><p><b>Format</b>: 0x0000 - 0xFFFF</p><p>Filter on selected encapsulated protocol (h_proto)</p><p>List of common protocols:</p><ul><li>IP: 0x0800</li><li>ARP: 0x0806</li><li>VLAN: 0x8100</li></ul>">
		  <td style="width: 50px;">
			  <input name="etht_cb" id="cb[7]" data-index="7" type="checkbox" <?=$filter->ind&128 ? 'checked="checked"' : '' ?> />128
		  </td>
		  <td class="label" title="Ethernet encapsulated protocol">
			  ETH type
		  </td>
		  <td colspan="1">
			  <input id="ETH_TYPE" name="ETH_TYPE" type="text" size="5" maxlength="5" onchange="filter_clear(this);" value="<?=$filter->ETH_TYPE ?>" />
		  </td>
		  <td class="label">
			  ETH type mask
		  </td>
		  <td>
			  <input id="ETH_TYPE_MASK" name="ETH_TYPE_MASK" type="text" size="14" maxlength="14" value="<?=$filter->ETH_TYPE_MASK?>" />
		  </td>
		  <td>
			  <?=select('ethmask_selection',    array('0xffff', '0xff00', '0x00ff', 'Other' => ''), array($filter->ETH_TYPE_MASK, ''), 'ETH_TYPE_MASK')?>
		  </td>
	  </tr>

	  <tr class="row" data-description="<h2>Ethernet source address</h2><p><b>Format</b>: XX:XX:XX:XX:XX:XX</p><p>Either - or : may be used as optional delimiter.</p>">
		  <td style="width: 50px;">
			  <input name="eths_cb" id="cb[6]" data-index="6" type="checkbox" <?=$filter->ind&64  ? 'checked="checked"' : '' ?> />&nbsp;64
		  </td>
		  <td class="label" title="Ethernet source address">
			  ETH SRC
		  </td>
		  <td colspan="1">
			  <input id="ETH_SRC" name="ETH_SRC" type="text" size="17" maxlength="17" onchange="filter_clear(this);" value="<?=$filter->ETH_SRC  ?>" />
		  </td>
		  <td class="label">
			  ETH SRC mask
		  </td>
		  <td>
			  <input id="ETH_SRC_MASK" name="ETH_SRC_MASK" type="text" size="17" maxlength="17" value="<?=$filter->ETH_SRC_MASK ?>" />
		  </td>
		  <td>
			  <?=select('ethsrcmask_selection', array('' => 'ffffffffffff', 'Multicast' => '010000000000', 'OUI' => 'ffffff000000', 'NIC' => '000000ffffff', '000000000000', 'Other' => ''), array($filter->ETH_SRC_MASK, ''), 'ETH_SRC_MASK')?>
		  </td>
	  </tr>

	  <tr class="row" data-description="<h2>Ethernet destination address</h2><p><b>Format</b>: XX:XX:XX:XX:XX:XX</p><p>Either - or : may be used as optional delimiter.</p>">
		  <td style="width: 50px;">
			  <input name="ethd_cb" id="cb[5]" data-index="5" type="checkbox" <?=$filter->ind&32  ? 'checked="checked"' : '' ?> />&nbsp;32
		  </td>
		  <td class="label" title="Ethernet destination address">
			  ETH DST
		  </td>
		  <td colspan="1">
			  <input id="ETH_DST" name="ETH_DST" type="text" size="17" maxlength="17" onchange="filter_clear(this);" value="<?=$filter->ETH_DST  ?>" />
		  </td>
		  <td class="label">
			  ETH DST mask
		  </td>
		  <td>
			  <input id="ETH_DST_MASK" name="ETH_DST_MASK" type="text" size="17" maxlength="17" value="<?=$filter->ETH_DST_MASK ?>" />
		  </td>
		  <td>
			  <?=select('ethdstmask_selection', array('' => 'ffffffffffff', 'Multicast' => '010000000000', 'OUI' => 'ffffff000000', 'NIC' => '000000ffffff', '000000000000', 'Other' => ''), array($filter->ETH_DST_MASK, ''), 'ETH_DST_MASK')?>
		  </td>
	  </tr>

	  <tr class="row" data-description="<h2>IP transport protocol</h2><p><b>Format</b>: 0x00 - 0xFF</p><p><a href=&quot;http://en.wikipedia.org/wiki/List_of_IP_protocol_numbers&quot; target=&quot;_blank&quot;>List of IP protocol numbers</a>.</p>">
		  <td style="width: 50px;">
			  <input name="ipp_cb"  id="cb[4]" data-index="4" type="checkbox" <?=$filter->ind&16  ? 'checked="checked"' : '' ?> />&nbsp;16
		  </td>
		  <td class="label" title="IP transport protocol">
			  IP proto
		  </td>
		  <td colspan="4">
			  <input id="IP_PROTO" name="IP_PROTO" type="text" size="5"  maxlength="5"  onchange="filter_clear(this);" value="<?=$filter->IP_PROTO ?>" style="width: 6em;" />
			  <?=select('ipproto_selection', array('UDP' => 17, 'TCP' => 6, 'ICMP' => 1, 'Other' => ''), array($filter->IP_PROTO, ''), 'IP_PROTO', array('style' => 'width: 7em;'))?>
		  </td>
	  </tr>

	  <tr class="row" data-description="<h2>IP source address</h2><p><b>Format</b>: AAA.BBB.CCC.DDD</p>">
		  <td style="width: 50px;">
			  <input name="ips_cb"  id="cb[3]" data-index="3" type="checkbox" <?=$filter->ind&8   ? 'checked="checked"' : '' ?> />&nbsp;&nbsp;8
		  </td>
		  <td class="label" title="IP source address">
			  IP SRC
		  </td>
		  <td colspan="1">
			  <input id="IP_SRC" name="IP_SRC" type="text" size="16" maxlength="16" onchange="filter_clear(this);" value="<?=$filter->IP_SRC   ?>" />
		  </td>
		  <td class="label">
			  IP SRC mask
		  </td>
		  <td>
			  <input id="IP_SRC_MASK" name="IP_SRC_MASK" type="text" size="16" maxlength="16" value="<?=$filter->IP_SRC_MASK	?>" />
		  </td>
		  <td>
			  <?=select('ipsmask_selection',    array('255.255.255.255', '255.255.255.0', 'Other' => ''), array($filter->IP_SRC_MASK, ''), 'IP_SRC_MASK')?>
		  </td>
	  </tr>

	  <tr class="row" data-description="<h2>IP destination address</h2><p><b>Format</b>: AAA.BBB.CCC.DDD</p>">
		  <td style="width: 50px;">
			  <input name="ipd_cb"  id="cb[2]" data-index="2" type="checkbox" <?=$filter->ind&4   ? 'checked="checked"' : '' ?> />&nbsp;&nbsp;4
		  </td>
		  <td class="label" title="IP destination address">
			  IP DST
		  </td>
		  <td colspan="1">
			  <input id="IP_DST" name="IP_DST" type="text" size="16" maxlength="16" onchange="filter_clear(this);" value="<?=$filter->IP_DST   ?>" />
		  </td>
		  <td class="label">
			  IP DST mask
		  </td>
		  <td>
			  <input id="IP_DST_MASK" name="IP_DST_MASK" type="text" size="16" maxlength="16" value="<?=$filter->IP_DST_MASK	?>" />
		  </td>
		  <td>
			  <?=select('ipdmask_selection', array('255.255.255.255', '255.255.255.0', 'Other' => ''), array($filter->IP_DST_MASK, ''), 'IP_DST_MASK')?>
		  </td>
	  </tr>

	  <tr class="row" data-description="<h2>Transport source port</h2><p><b>Format</b>: 0x0000 - 0xffff</p><p>Parsed as decimal by default or hex if using 0x prefix.</p>">
		  <td style="width: 50px;">
			  <input name="sprt_cb" id="cb[1]" data-index="1" type="checkbox" <?=$filter->ind&2   ? 'checked="checked"' : '' ?> />&nbsp;&nbsp;2
		  </td>
		  <td class="label" title="Source port">
			  SRC port
		  </td>
		  <td colspan="1">
			  <input id="SRC_PORT" name="SRC_PORT" type="text" size="5"  maxlength="5"  onchange="filter_clear(this);" value="<?=$filter->SRC_PORT ?>" /></td>
		  <td class="label">
			  SRC port mask
		  </td>
		  <td>
			  <input id="SRC_PORT_MASK" name="SRC_PORT_MASK" type="text" size="5"  maxlength="5" value="<?=$filter->SRC_PORT_MASK ?>" />
		  </td>
		  <td>
			  <?=select('portsmask_selection',  array('0xffff', '0x0000', 'Other' => ''), array($filter->SRC_PORT_MASK, ''), 'SRC_PORT_MASK')?>
		  </td>
	  </tr>

	  <tr class="row" data-description="<h2>Transport destination port</h2><p><b>Format</b>: 0x0000 - 0xffff</p><p>Parsed as decimal by default or hex if using 0x prefix.</p>">
		  <td style="width: 50px;">
			  <input name="dprt_cb" id="cb[0]" data-index="0" type="checkbox" <?=$filter->ind&1   ? 'checked="checked"' : '' ?> />&nbsp;&nbsp;1
		  </td>
		  <td class="label" title="Destination port">
			  DST port
		  </td>
		  <td colspan="1">
			  <input id="DST_PORT" name="DST_PORT" type="text" size="5"  maxlength="5"  onchange="filter_clear(this);" value="<?=$filter->DST_PORT ?>" />
		  </td>
		  <td class="label">
			  DST port mask
		  </td>
		  <td>
			  <input id="DST_PORT_MASK" name="DST_PORT_MASK" type="text" size="5"   maxlength="5" value="<?=$filter->DST_PORT_MASK ?>" />
		  </td>
		  <td>
			  <?=select('portdmask_selection',  array('0xffff', '0x0000', 'Other' => ''), array($filter->DST_PORT_MASK, ''), 'DST_PORT_MASK')?>
		  </td>
	  </tr>

	  <tr class="row address" data-description="<h2>Destination address</h2><p>Where the MP should send the matching packets.</p><p>Usually you select <b>ethernet multicasting</b> and use an address where the least significant bit in the first octet is 1. <b>File</b> output stores into a local trace-file using the DPMI capture format. <b>Discard</b> will cause the filter to drop all matching packets, e.g. to filter out all SSH traffic you can first add a filter which matches SSH with a discard destination and then a real filter capturing packets. <b>TCP and UDP</b> destinations encapsulates and forwards packets to the entered destination.</p><p>Note: TCP requires a running TCP consumer and will not capture any packets until it has successfully connected!</p><h2>Adresses table</h2><div class='address'></div>">
		  <td>
			  &nbsp;
		  </td>
		  <td class="label" title="Destination address">
			  Destination
		  </td>
		  <td>
			  <input id="DESTADDR" name="DESTADDR" type="text" size="23" maxlength="23" onchange="filter_clear(this);" value="<?=$filter->DESTADDR?>"/>
		  </td>
		  <td class="label" title="Destination type">
			  Address type
		  </td>
		  <td colspan="2">
			  <?=select('TYPE', array('File' => 0, 'Ethernet multicast' => 1, 'UDP' => 2, 'TCP' => 3, 'Discard' => 4), array($filter->TYPE))?>
		  </td>
	  </tr>

	  <tr class="row" data-description="<h2>Capture length</h2><p>How many bytes of the captured packet to save, excluding metadata such as timestamp and wirelength.</p><p>Note: the MP should be able to transfer packets to the destination without fragmentation. Each packet is encapsulated with a 36 byte capture header and possibly a 16 byte send header. For ethernet multicasting on a network with a MTU of 1500 the maximum caplen will be 1500-14-16-36=1434 bytes.</p><p>For full-size packet capture on a network with MTU 1500 use 1514 (requires MTU greater than 1566 on MA network, e.g. 9000).">
		  <td>
			  &nbsp;
		  </td>
		  <td class="label" title="Capture length">
			  Caplen
		  </td>
		  <td colspan="4">
			  <input id="CAPLEN" name="CAPLEN" type="text" size="14" maxlength="4" onchange="filter_clear(this);" value="<?=$filter->CAPLEN?>" />
		  </td>
	  </tr>

	  <tr>
	    <td colspan="6" style="text-align: right;">
<?php if ( $filter_exist ){ ?>
	      <input type="submit" name="action" value="Update" />
<?php } else { ?>
	      <input type="submit" name="action" value="Add" />
<?php } ?>
	      <input type="button" value="Parse" onclick="filter_validate();" />
	      <input type="submit" name="cancel" onclick="filter_cancel();" value="Cancel" />
	    </td>
	  </tr>
	</table>
      </form>
</div>
