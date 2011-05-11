<?php //  -*- mode:html;  -*- ?>
<div class="alert">
  <p><?=$message?></p>
  <p>
<?php foreach ($alt as $v){ ?>
    <a href="?confirm=<?=$v?>"><?=$v?></a>&nbsp;&nbsp;&nbsp;
<?php } ?>
  </p>
</div>
