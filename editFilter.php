<?
require("sessionCheck.php");
require("config.inc");
require_once("model/MP.php");

$FILTER_ID=$_GET["filter_id"];
$toggle=0;
$can_set_mp = false;

$mp = MP::from_mampid($_GET['MAMPid']);
$all_mp = MP::selection();
if ( $mp == null ){
  die("No measurement point named {$_GET['MAMPid']}!");
}

$filter = $mp->filter($_GET['filter_id']);
if ( $filter == null ){
  die("Measurement point {$mp->MAMPid} has no filter named {$_GET['filter_id']}!");
}

/**
 * callback for array_map used by select.
 */
$build_options_default = null; /* @hack: this is set by select. */
function build_options($key, $value){
  global $build_options_default;
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
  global $build_options_default;
  $build_options_default = null;
  if ( $default != null ){
    $tmp = array_values($normalized);
    foreach ( $default as $value ){
      if ( in_array($value, $tmp) ){
	$build_options_default = $value;
	break;
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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en-US" xml:lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="http://www.bth.se/bth/styles/bth.css" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <link rel="shortcut icon" type="image/x-icon" href="http://www.bth.se/favicon.ico" />
    <title>MArC :: Add filter</title>
    <script type="text/javascript" src="js/filter.js"></script>
  </head>
  
  <body class="bthcss">

    <div id="filter">
      <form action="editFilter2.php?SID=<?=$sid?>" method="post" name="myForm" target="view">
	<input type="hidden" name="old_filter_id" value="<?=$filter->filter_id?>" />

	<h1>Filter Specification</h1>
	<noscript>
	<p class="notice">This page requires javascript to function properly!</p>
	<p class="notice">You need to manually update index!</p>
	</noscript>

	<p><b>MP</b>: <?=$mp->name?></p>
	<p>
	  INDEX     <input id="index"     name="index"     type="text" size="14" value="<?=$filter->ind?>" />
	  FILTER ID <input id="filter_id" name="filter_id" type="text" size="14" value="<?=$filter->filter_id?>" maxlength="14" />
	</p>

	<h2>Packet specification</h2>
	<table border="0" cellspacing="1">
	  <tr><td style="width: 50px;"><input name="cicb"   type="checkbox" onchange="updateIndex();" <?=$filter->ind&512 ? 'checked="checked"' : '' ?> />512</td>          <td class="label">CI      </td>	<td colspan="4"><input name="ci"       type="text" size="8"  maxlength="8"  value="<?=$filter->CI_ID	?>" /></td></tr>
	  <tr><td style="width: 50px;"><input name="vlancb" type="checkbox" onchange="updateIndex();" <?=$filter->ind&256 ? 'checked="checked"' : '' ?> />256</td>          <td class="label">VLAN_TCI</td>	<td colspan="1"><input name="vlan_tci" type="text" size="5"  maxlength="5"  value="<?=$filter->VLAN_TCI ?>" /></td>    <td class="label">VLAN_TCI_MASK</td><td><input name="vlan_tci_mask" type="text" size="14" maxlength="14" value="<?=$filter->VLAN_TCI_MASK?>" /></td><td><?=select('vlanmask',   array('ffff', 'ff00', '00ff', 'Other' => ''), array($filter->VLAN_TCI_MASK, ''), 'vlan_tci_mask')?></td></tr>
	  <tr><td style="width: 50px;"><input name="ethtcb" type="checkbox" onchange="updateIndex();" <?=$filter->ind&128 ? 'checked="checked"' : '' ?> />128</td>          <td class="label">ETH_TYPE</td>	<td colspan="1"><input name="eth_type" type="text" size="5"  maxlength="5"  value="<?=$filter->ETH_TYPE ?>" /></td>    <td class="label">ETH_TYPE_MASK</td><td><input name="eth_type_mask" type="text" size="14" maxlength="14" value="<?=$filter->ETH_TYPE_MASK?>" /></td><td><?=select('ethmask',    array('ffff', 'ff00', '00ff', 'Other' => ''), array($filter->ETH_TYPE_MASK, ''), 'eth_type_mask')?></td></tr>
	  <tr><td style="width: 50px;"><input name="ethscb" type="checkbox" onchange="updateIndex();" <?=$filter->ind&64  ? 'checked="checked"' : '' ?> />&nbsp;64</td>     <td class="label">ETH_SRC </td>	<td colspan="1"><input name="eth_src"  type="text" size="17" maxlength="17" value="<?=$filter->ETH_SRC	?>" /></td>    <td class="label">ETH_SRC_MASK </td><td><input name="eth_src_mask"  type="text" size="17" maxlength="17" value="<?=$filter->ETH_SRC_MASK ?>" /></td><td><?=select('ethsrcmask', array('ffffffffffff', '000000000000', 'Other' => ''), array($filter->ETH_SRC_MASK, ''), 'eth_src_mask')?></td></tr>
	  <tr><td style="width: 50px;"><input name="ethdcb" type="checkbox" onchange="updateIndex();" <?=$filter->ind&32  ? 'checked="checked"' : '' ?> />&nbsp;32</td>     <td class="label">ETH_DST </td>	<td colspan="1"><input name="eth_dst"  type="text" size="17" maxlength="17" value="<?=$filter->ETH_DST	?>" /></td>    <td class="label">ETH_DST_MASK </td><td><input name="eth_dst_mask"  type="text" size="17" maxlength="17" value="<?=$filter->ETH_DST_MASK ?>" /></td><td><?=select('ethdstmask', array('ffffffffffff', '000000000000', 'Other' => ''), array($filter->ETH_DST_MASK, ''), 'eth_dst_mask')?></td></tr>
	  <tr><td style="width: 50px;"><input name="ippcb"  type="checkbox" onchange="updateIndex();" <?=$filter->ind&16  ? 'checked="checked"' : '' ?> />&nbsp;16</td>     <td class="label">IP_PROTO</td>	<td colspan="4"><input name="ip_proto" type="text" size="5"  maxlength="5"  value="<?=$filter->IP_PROTO ?>" style="width: 6em;" /> <?=select('ipproto_predef', array('UDP' => 17, 'TCP' => 6, 'ICMP' => 1, 'Other' => ''), array($filter->protocol(), ''), 'ip_proto', array('style' => 'width: 7em;'))?></td></tr>
	  <tr><td style="width: 50px;"><input name="ipscb"  type="checkbox" onchange="updateIndex();" <?=$filter->ind&8   ? 'checked="checked"' : '' ?> />&nbsp;&nbsp;8</td><td class="label">IP_SRC  </td>	<td colspan="1"><input name="ip_src"   type="text" size="16" maxlength="16" value="<?=$filter->IP_SRC	?>" /></td>    <td class="label">IP_SRC_MASK  </td><td><input name="ip_src_mask"   type="text" size="16" maxlength="16" value="<?$filter->IP_SRC_MASK	?>" /></td><td><?=select('ipsmask',    array('255.255.255.255', '255.255.255.0', 'Other' => ''), array($filter->IP_SRC_MASK, ''), 'ip_src_mask')?></td></tr>
	  <tr><td style="width: 50px;"><input name="ipdcb"  type="checkbox" onchange="updateIndex();" <?=$filter->ind&4   ? 'checked="checked"' : '' ?> />&nbsp;&nbsp;4</td><td class="label">IP_DST  </td>	<td colspan="1"><input name="ip_dst"   type="text" size="16" maxlength="16" value="<?=$filter->IP_DST	?>" /></td>    <td class="label">IP_DST_MASK  </td><td><input name="ip_dst_mask"   type="text" size="16" maxlength="16" value="<?$filter->IP_DST_MASK	?>" /></td><td><?=select('ipdmask',    array('255.255.255.255', '255.255.255.0', 'Other' => ''), array($filter->IP_DST_MASK, ''), 'ip_dst_mask')?></td></tr>
	  <tr><td style="width: 50px;"><input name="sprtcb" type="checkbox" onchange="updateIndex();" <?=$filter->ind&2   ? 'checked="checked"' : '' ?> />&nbsp;&nbsp;2</td><td class="label">SRC_PORT</td>	<td colspan="1"><input name="src_port" type="text" size="5"  maxlength="5"  value="<?=$filter->SRC_PORT ?>" /></td>    <td class="label">SRC_PORT_MASK</td><td><input name="src_port_mask" type="text" size="5"	 maxlength="5"	value="<?$filter->SRC_PORT_MASK ?>" /></td><td><?=select('portsmask',  array('ffff', '0000', 'Other' => ''), array($filter->SRC_PORT_MASK, ''), 'src_port_mask')?></td></tr>
	  <tr><td style="width: 50px;"><input name="dprtcb" type="checkbox" onchange="updateIndex();" <?=$filter->ind&1   ? 'checked="checked"' : '' ?> />&nbsp;&nbsp;1</td><td class="label">DST_PORT</td>	<td colspan="1"><input name="dst_port" type="text" size="5"  maxlength="5"  value="<?=$filter->DST_PORT ?>" /></td>    <td class="label">DST_PORT_MASK</td><td><input name="dst_port_mask" type="text" size="5"	 maxlength="5"	value="<?$filter->DST_PORT_MASK ?>" /></td><td><?=select('portdmask',  array('ffff', '0000', 'Other' => ''), array($filter->DST_PORT_MASK, ''), 'dst_port_mask')?></td></tr>
	  <tr><td style="width: 50px;">&nbsp;</td><td class="label">DESTADDR</td><td><input name="destaddr" type="text" size="23" maxlength="23" value="<?=$filter->DESTADDR?>"/></td><td class="label">TYPE</td><td colspan="2"><?=select('stream_type', array('File' => 0, 'Ethernet multicast' => 1, 'UDP' => 2, 'TCP' => 3), array($filter->TYPE))?><p>Note: TCP requires a running TCP consumer!</p></td></tr>
	  <tr><td style="width: 50px;">&nbsp;</td><td class="label">CAPLEN</td><td colspan="4"><input name="caplen" type="text" size="14" maxlength="4" value="<?=$filter->CAPLEN?>" /></td></tr>

<?php if ( $can_set_mp ) { ?>	  
	  <tr><th colspan="6">MP Receiving Filter</th></tr>
	  <tr><td colspan="6">DO NOT CHANGE THIS IN EDIT MODE!!!!<br/>DELETE OLD RULE AND MAKE A NEW!!!!!</td></tr>
	  
	  <tr>
	    <th>&nbsp;</th>
	    <th>Name</th>
	    <th colspan="3">Comment</th>
	    <th>Max filters</th>
	  </tr>
	  
<?php foreach ($all_mp as $cur){ ?>
<?php $color = ($toggle++ % 2 == 0) ? "CCC" : "DDD"; ?>
	  <tr style="background: #<?=$color?>;">
	    <td><input type="radio" name="mp" value="<?=$cur->MAMPid?>" /></td>
	    <td><?=$cur->name?></td>
	    <td colspan="3"><?=$cur->comment?></td>
	    <td><?=$cur->maxFilters?></td>
	  </tr>
<?php } /* foreach $all_mp */ ?>
<?php } /* $can_set_mp */ ?>
	  <tr>
	    <td colspan="5">&nbsp;</td>
	    <td><input type="submit" value="Update Filter" />&nbsp;<input type="reset" value="Reset" /></td>
	  </tr>
	</table>
      </form>
    </div>
  </body>

  <script type="text/javascript" />
    /* disable index-modification if javascript is enabled (it is calculated automatically) */
    document.getElementById('index').disabled = true;
  </script>

</html>
