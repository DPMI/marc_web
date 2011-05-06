<?php

require_once('BasicObject.php');

class Menu extends BasicObject {
  static protected function table_name(){
    return 'mainmenu';
  }

  public function href(){
    switch ( $this->type ){
    case 0:  return "index2.php/display/{$this->url}";
    default: return "index2.php/main/{$this->url}";
    }
  }
}

?>