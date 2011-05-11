<h1>Accounts</h1>

<table border="0" cellspacing="0" width="100%" class="list">
  <tr>
    <th><a href="<?=$index?>/account?order=uname&amp;asc=<?=$ascinv?>">Uname</a></th>
    <th><a href="<?=$index?>/account?order=status&amp;asc=<?=$ascinv?>">Status</a></th>
    <th><a href="<?=$index?>/account?order=comment&amp;asc=<?=$ascinv?>">Comment</th>
    <th><a href="<?=$index?>/account?order=time&amp;asc=<?=$ascinv?>">Date/Time</a></th>
    <th><a href="<?=$index?>/account?order=Name&amp;asc=<?=$ascinv?>">Name</th>
    <th><a href="<?=$index?>/account?order=Email&amp;asc=<?=$ascinv?>">E-Mail</a></th>
    <th>&nbsp;</th>
  </tr>

<?php foreach($all as $account){ ?>
  <tr class="<?=($toggle++ % 2 == 0) ? "even" : "odd"?>">
    <td><?=$account->uname?></td>
    <td><?=$account->status?></td>
    <td><?=$account->comment?></td>
    <td><?=$account->time?></td>
    <td><?=$account->Name?></td>
    <td><?=$account->Email?></td>
    <td>
      <a href="<?=$index?>/account/edit/<?=$account->id?>"><img width="12" height="13"  border="0" alt="Edit" title="Edit" src='<?=$root?>button_edit.png'/></a>
      <a href="<?=$index?>/account/del/<?=$account->id?>"><img width="12" height="13"  border="0" alt="Delete" title="Delete" src='<?=$root?>button_drop.png'/></a>
    </td>
  </tr>
<?php } ?>
</table>
