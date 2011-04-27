<?php

require_once('BasicObject.php');
require_once('Filter.php');
require_once('MPStatus.php');

class MP extends BasicObject {
  static protected function table_name(){
    return 'measurementpoints';
  }

  public static function from_mampid($mampid){
    return static::from_field('MAMPid', $mampid);
  }

  public function filter_table(){
    return "{$this->MAMPid}_filterlist";
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
    
    $result = $db->query("SELECT * FROM {$this->MAMPid}_filterlist");
    if ( $result == null ){
      return array();
    }

    $filters = array();
    while ( $row = $result->fetch_assoc() ){
      $filters[] = new Filter($this, $row);
    }

    return $filters;
  }

  public function filter($id){
    global $db;
    
    $sql = "SELECT * FROM {$this->MAMPid}_filterlist WHERE filter_id = '" . mysql_real_escape_string($id) . "' LIMIT 1";
    $result = $db->query($sql);
    $row = $result->fetch_assoc();
    if ( !$row ){
      return null;
    }
    return new Filter($this, $row);
  }

  public function stats($limit=null){
    if ( $this->is_authorized() ){
      return new MPStatus($this->MAMPid, $limit);
    } else {
      return null;
    }
  }

  public function reload_filter($id){
    /* 65 is filter reload event */
    $message = pack("Na16N", 65, $this->MAMPid, $id);
    $this->send($message);
  }

  private function send($message){
    $ip = $this->ip;
    $port = $this->port;
    $fp = fsockopen("udp://$ip", $port, $errno, $errstr);
    if ( !$fp ){
      throw new RuntimeError("Could not open MP socket (code $errno): $errstr");
    }
    fwrite($fp, $message);
    fclose($fp);
  }

}

?>