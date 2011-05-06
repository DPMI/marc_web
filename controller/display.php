<?php

require_once('model/Page.php');

$page = Page::from_url($path);

if ( $page->accesslevel > $u_access ){
  header("Location: loginDenied.php");
  exit;
}

echo html_entity_decode($page->text);
echo "<p>Last Modified: {$page->date}</p>";

?>
