<?php

require_once('Controller.php');
require_once('model/Page.php');

class displayController extends Controller {
  public function index($path){
    $page = Page::from_url(implode('/',$path));
    parent::validate_access($page->accesslevel);

    return html_entity_decode($page->text) . "\n<p>Last Modified: {$page->date}</p>\n";
  }

  public function _path(array $path){
    return $this->index($path);
  }
}

?>
