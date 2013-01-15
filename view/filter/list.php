<?php //  -*- mode:html;  -*- ?>
<?php foreach ( $mps as $mp ){ ?>
<h1><a href="<?=$index?>/MP">Measurement Points</a> &gt; <a href="<?=$index?>/MP/view/<?=$mp->MAMPid?>"><?=$mp->name?></a> &gt; Filters</h1>
<h2>
  <span class="mampid"><?=$mp->name?></span>
  <a href="<?=$index?>/MP/verify/<?=$mp->MAMPid?>"><img width="12" height="13" border="0" alt="Verify all filters" title="Verify all filters" src="<?=$root?>button_properties.png" /></a>
  <a href="<?=$index?>/MP/filter_add/<?=$mp->MAMPid?>"><img width="12" height="13"  border="0" alt="Add filter" title="Add filter" src="<?=$root?>button_insert.png" /></a>
</h2>

<table border="0" cellspacing="0" width="100%" class="list">
  <tr>
    <th valign="bottom">Filter ID</th>
    <th valign="bottom">Capture<br/>interface</th>
    <th valign="bottom">VLAN TCI/<br/>MASK</th>
    <th valign="bottom">ETH type/<br/>MASK</th>
    <th valign="bottom">ETH SRC/MASK</th>
    <th valign="bottom">ETH DST/MASK</th>
    <th valign="bottom">IP<br/>protocol</th>
    <th valign="bottom">IP SRC/<br/>MASK</th>
    <th valign="bottom">IP DST/<br/>MASK</th>
    <th valign="bottom">SRC PORT/<br/>MASK</th>
    <th valign="bottom">DST PORT/<br/>MASK</th>
    <th valign="bottom">Destination</th>
    <th valign="bottom">Capture<br/>length</th>
    <th valign="bottom">&nbsp;</th>
  </tr>

  <?php $toggle = 0; ?>
  <?php foreach ( $mp->all_filters() as $filter ){ ?>
  <tr class="<?=($toggle++ % 2 == 0) ? "even" : "odd"?>">
    <td valign="top"><a href="<?=$index?>/MP/filter/<?=$mp->MAMPid?>/<?=$filter->filter_id?>" class="id"><?=$filter->filter_id?></a></td>
    <td valign="top"><?=$filter->index & 512 ? $filter->CI : '-' ?></td>
    <td valign="top"><?=$filter->index & 256 ? "$filter->VLAN_TCI<br/>/$filter->VLAN_TCI_MASK" : '-' ?></td>
    <td valign="top"><?=$filter->index & 128 ? "$filter->ETH_TYPE<br/>/$filter->ETH_TYPE_MASK" : '-' ?></td>
    <td valign="top"><?=$filter->index &  64 ? "$filter->ETH_SRC<br/>/$filter->ETH_SRC_MASK"   : '-' ?></td>
    <td valign="top"><?=$filter->index &  32 ? "$filter->ETH_DST<br/>/$filter->ETH_DST_MASK"   : '-' ?></td>
    <td valign="top"><?=$filter->index &  16 ? $filter->IP_PROTO : '-' ?></td>
    <td valign="top"><?=$filter->index &   8 ? "$filter->IP_SRC<br/>/$filter->IP_SRC_MASK"     : '-' ?></td>
    <td valign="top"><?=$filter->index &   4 ? "$filter->IP_DST<br/>/$filter->IP_DST_MASK"	   : '-' ?></td>
    <td valign="top"><?=$filter->index &   2 ? "$filter->SRC_PORT<br/>/$filter->SRC_PORT_MASK" : '-' ?></td>
    <td valign="top"><?=$filter->index &   1 ? "$filter->DST_PORT<br/>/$filter->DST_PORT_MASK" : '-' ?></td>
    <td valign="top"><?=$filter->destaddr?>/<?=$filter->type?></td>
    <td valign="top"><?=$filter->caplen?></td>
    <td width="45">
      <a href="<?=$index?>/MP/filter/<?=$mp->MAMPid?>/<?=$filter->filter_id?>"><img width="12" height="13"  border="0" alt="Edit" title="Edit filter" src='<?=$root?>button_edit.png'/></a>
      <a href="<?=$index?>/MP/filter_del/<?=$mp->MAMPid?>/<?=$filter->filter_id?>" class="delete"><img width="12" height="13"  border="0" alt="Drop" title="Drop filter" src='<?=$root?>button_drop.png'/></a>
      <a href="<?=$index?>/MP/filter_verify/<?=$mp->MAMPid?>/<?=$filter->filter_id?>"><img width="12" height="13"  border="0" alt="Verify" title="Verify filter" src='<?=$root?>button_properties.png'/></a>
    </td>
  </tr>
  <?php } /* foreach $filter */ ?>
</table>
<?php } /* foreach $mps */ ?>

