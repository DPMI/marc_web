<?php //  -*- mode:html;  -*- ?>
<h1>DPMI Measurement Area Control Daemon</h1>
<?php if ( isset($_SESSION['passwd_warning']) ){ ?>
<div class="notice" style="border: 1px dashed black; background-position:left center;">
  <p>Your password must be updated. The hashing algorithm changed in 0.7 due to security issues. Your current password will continue to work in this version but future versions may remove this compatability.</p>
  <p>The password can be updated on the <a href="<?=$index?>/account/self?>">account</a> page.</p>
</div>
<?php } ?>

<h1>Messages</h1>
