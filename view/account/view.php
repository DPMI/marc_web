<?php //  -*- mode:html;  -*- ?>
<?php if ( !$admin ){ ?>
<h1>Account settings</h1>
<?php } else if ( $exist ){ ?>
<h1><a href="<?=$index?>/account">Account</a> &gt Edit account</h1>
<?php } else { ?>
<h1><a href="<?=$index?>/account">Account</a> &gt Add new account</h1>
<?php } ?>

<form action="<?=$index?>/account/submit" method="post">
  <table border="0" class="form">
<?php if ( $admin ){ ?>
    <tr>
      <td class="label">Username</td>
      <td><input name="uname" type="text" size="20" maxlength="80" value="<?=$account->uname?>"></td>
      <td>&nbsp;</td>
    </tr>
<?php } else { /* $admin */ ?>
    <tr>
      <td class="label">Username</td>
      <td><?=$account->uname?></td>
      <td>&nbsp;</td>
    </tr>
<?php } /* $admin */ ?>
    <tr>
      <td class="label">Password</td>
      <td><input name="passwd-1" type="password" size="20" maxlength="80" value="" autocomplete="off"></td>
      <td>Leave blank to remain unchanged</td>
    </tr>
    <tr>
      <td class="label">Confirm</td>
      <td><input name="passwd-2" type="password" size="20" maxlength="80" value="" autocomplete="off"></td>
      <td>&nbsp;</td>
    </tr>

<?php if ( $admin ){ ?>
    <tr>
      <td class="label">Status</td>
      <td>
	<select name="status">
	  <option value="0" <?= $account->status==0 ? 'selected="selected"' : '' ?>>Public</option>
	  <option value="1" <?= $account->status==1 ? 'selected="selected"' : '' ?>>Member</option>
	  <option value="2" <?= $account->status==2 ? 'selected="selected"' : '' ?>>Administrator</option>
	</select>
      </td>
    </tr>

    <tr>
      <td class="label">Comment</td>
      <td><input name="comment" type="text" size="20" maxlength="80" value="<?=$account->comment?>"></td>
      <td>Only visible to administrators</td>
    </tr>
<?php } /* $admin */ ?>

    <tr>
      <td class="label">Name</td>
      <td><input name="name" type="text" size="30" maxlength="80" value="<?=$account->Name?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td class="label">Email</td>
      <td><input name="email" type="text" size="30" maxlength="80" value="<?=$account->Email?>"></td>
      <td>&nbsp;</td>
    </tr>
    
    <tr>
      <td colspan="3">
	<input type="submit" value="<?=$exist ? "Update user" : "Add" ?>"/>
	<input type="submit" value="Cancel" />
      </td>
    </tr>

    <input type="hidden" name="id" value="<?=$account->id?>" / >
  </table>
</form>
