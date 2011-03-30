<?php

require_once('BasicObject.php');

class MP extends BasicObject {
  static protected function table_name(){
    return 'measurementpoints';
  }

  public function status(){
    global $db;
    if ( !$this->is_authorized() ){
      return "Not Auth";
    }

    $result = $db->query('SELECT COUNT(*) FROM ${this->MAMPid}_filterlist');

    if ( $result[0] > 0 ){
      return "Capturing";
    } else {
      return "Idle";
    }
  }

  public function is_authorized(){
    return true;
    return strlen($this->MAMPid) > 0;
  }
}

?>