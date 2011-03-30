<?php

require_once('BasicObject.php');
require_once('Filter.php');

class MP extends BasicObject {
  static protected function table_name(){
    return 'measurementpoints';
  }

  public function status(){
    global $db;
    if ( !$this->is_authorized() ){
      return "Not Auth";
    }

    $result = $db->query("SELECT COUNT(*) FROM {$this->MAMPid}_filterlist");
    $row = $result->fetch_row();

    if ( $row[0] > 0 ){
      return "Capturing";
    } else {
      return "Idle";
    }
  }

  public function is_authorized(){
    return strlen($this->MAMPid) > 0;
  }

  public function filters(){
    global $db;
    
    $filters = array();
    $result = $db->query("SELECT * FROM {$this->MAMPid}_filterlist");
    while ( $row = $result->fetch_assoc() ){
      $filters[] = new Filter($row);
    }

    return $filters;
  }
}

?>