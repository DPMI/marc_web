<style>
h2 {
  margin: 0;
}
pre {
  border: 1px dashed #000;
  margin: 0;
  margin-bottom: 15px;
  padding: 10px;
  overflow: auto;
}
</style>
<h1>500 Internal server error</h1>
<p>This was not supposed to happen. Please notify the responsible party and make sure to copy the full message.</p>
<p><?=$exception->getMessage()?></p>

<h2>Traceback</h2>
<pre>-- <?=$exception->getFile()?>(<?=$exception->getLine()?>): <?=get_class($exception)?>
<?=$exception->getTraceAsString()?></pre>

<h2>GET</h2>
<pre><?=print_r($_GET, true)?></pre>

<h2>POST</h2>
<pre><?=print_r($_POST, true)?></pre>

<h2>SERVER</h2>
<pre><?=print_r($_SERVER, true)?></pre>

<h2>Configuration</h2>
<pre>
<?php global $version, $DB_SERVER, $prefix, $root, $usergroup ?>
Version: <?=$version?>
Database: <?=$DB_SERVER?>
Prefix: <?=$prefix?>
Root: <?=$root?>
Group: <?=print_r($usergroup, true)?>
</pre>
