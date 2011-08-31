<?php

require_once('BasicObject.php');
require_once('Filter.php');
require_once('MPStatus.php');

define('DRIVER_RAW', 1);
define('DRIVER_PCAP', 2);
define('DRIVER_DAG', 4);

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
      return "Not authorized";
    }

    if ( $this->status == 4 ){
      return "Distress";
    }

    $result = $db->query("SELECT COUNT(*) FROM {$this->MAMPid}_filterlist");
    if ( !$result ){
      return "Invalid";
    }

    $row = $result->fetch_row();

    if ( $row[0] > 0 ){
      return "Capturing";
    } else {
      return "Idle";
    }
  }

  public function drivers_str(){
    $drivers = array();
    if ( $this->drivers & DRIVER_RAW  ) $drivers[] = 'raw';
    if ( $this->drivers & DRIVER_PCAP ) $drivers[] = 'pcap';
    if ( $this->drivers & DRIVER_DAG  ) $drivers[] = 'dag';
    return implode($drivers,', ');
  }

  public function is_authorized(){
    return strlen($this->MAMPid) > 0;
  }

  public function filter_count(){
    global $db;
    
    $result = $db->query("SELECT COUNT(*) FROM {$this->MAMPid}_filterlist");
    if ( $result == null ){
      return false; /* what is a good value to indicate this failure? */
    }
    $row = $result->fetch_row();
    return $row[0];
  }

  public function filters(){
    global $db;
    
    $result = $db->query("SELECT * FROM {$this->MAMPid}_filterlist ORDER BY filter_id ASC");
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
    /* 66 is filter reload event */
    $message = pack("Na16N", 66, $this->MAMPid, $id);
    $this->send($message);
  }

  public function del_filter($id){
    /* 69 is filter del event */
    $message = pack("Na16N", 69, $this->MAMPid, $id);
    $this->send($message);
  }
  
  public function generate_mampid(){
    $this->MAMPid = $this->name . substr($this->mac,15,2);
    return $this->MAMPid;
  }

  public function auth(){
    /* 129 is auth request */
    $message = pack("N", 129);
    $this->send($message);
  }

  public function send($message){
    $ip = $this->ip;
    $port = $this->port;
    $fp = fsockopen("udp://$ip", $port, $errno, $errstr);
    if ( !$fp ){
      throw new RuntimeError("Could not open MP socket (code $errno): $errstr");
    }
    fwrite($fp, $message);
    fclose($fp);
  }

  public function delete(){
    global $db, $rrdbase;
    @unlink("$rrdbase/{$this->MAMPid}.rrd");
    for($i=0; $i<$this->noCI; $i++){
      @unlink("$rrdbase/{$this->MAMPid}_CI{$i}.rrd");
    }
    $db->query("DROP TABLE IF EXISTS {$this->MAMPid}_filterlist") or die($db->error);
    $db->query("DROP TABLE IF EXISTS {$this->MAMPid}_filterlistverify") or die($db->error);
    $db->query("DROP TABLE IF EXISTS {$this->MAMPid}_CIload") or die($db->error);
    $db->query("DROP TABLE IF EXISTS {$this->MAMPid}_ci") or die($db->error);
    parent::delete();
  }
}

?>