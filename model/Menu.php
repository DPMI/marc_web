<?php

require_once('BasicObject.php');

class Menu extends BasicObject {
  static protected function table_name(){
    return 'mainmenu';
  }

  public function href(){
    global $index;
    switch ( $this->type ){
    case 0:  return "{$index}/display/{$this->url}";
    default: return "{$index}/{$this->url}";
    }
  }
}

?>