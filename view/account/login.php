<?php /* -*- mode: html -*- */ ?>

<h1>Login</h1>

<?php if ( isset($msg) ){ ?>
<div class="alert">
	<?php foreach ( $msg as $text ){ ?>
	<p><?=$text?></p>
	<?php } ?>
</div>
<?php } ?>

<p>This site uses cookies and sessions.</p>

<div style="width: 260px;">
	<form action="<?=$index?>/account/login?submit" method="post">
		<table width="100%">
			<tr>
				<td>Username</td>
				<td><input type="text" name="uName" autofocus /></td>
			</tr>
			<tr>
				<td>Password</td>
				<td><input type="password" name="pWord" /></td>
			</tr>
			<tr>
				<td colspan="2" style="text-align: center;">
					<input type="submit" value="Login" />
				</td>
			</tr>
		</table>
	</form>
</div>
