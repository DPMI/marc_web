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

  public function status(){
	  global $db, $mp_timeout;
    if ( !$this->is_authorized() ){
      return "Not authorized";
    }

    if ( time()-strtotime($this->time) > $mp_timeout ){
	    return "Timeout";
    }

    if ( $this->status == 4 ){
      return "Distress";
    } else if ( $this->status == 5 ){
      return "Stopped";
    }

    $num_filters = $this->filter_count();
    if ( $num_filters > 0 ){
      return "Capturing";
    } else {
      return "Idle";
    }
  }

  public function ping(){
	  global $use_ping, $root;
	  if ( !$use_ping ){ return ''; }
	  return "<img src=\"{$root}ping.php?MAMPid={$this->MAMPid}\" alt=\"\" />";
  }

  public function drivers_str(){
    $drivers = array();
    if ( $this->drivers & DRIVER_RAW  ) $drivers[] = 'raw';
    if ( $this->drivers & DRIVER_PCAP ) $drivers[] = 'pcap';
    if ( $this->drivers & DRIVER_DAG  ) $drivers[] = 'dag';
    return implode($drivers,', ');
  }

  public function ifaces(){
    return str_replace(";", ", ", $this->CI_iface);
  }

  public function version_str(){
    return str_replace(";", ", ", $this->version);
  }

  public function is_authorized(){
    return strlen($this->MAMPid) > 0;
  }

  public function filter_count(){
    global $db;

    /* MPs which isn't authorized cannot have filters */
    if ( !$this->is_authorized() ){
	    return 0;
    }

    return Filter::count(array('mp' => $this->id));
  }

  public function all_filters(){
	  return Filter::selection(array('mp' => $this->id));
  }

  public function filter_by_id($id){
	  $all = Filter::selection(array('mp' => $this->id, 'filter_id' => $id, '@limit' => 1));
	  return $all[0];
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

  public function stop(){
    /* 130 is termination request */
    $message = pack("N", 130);
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
    global $db;
    $db->query("DROP TABLE IF EXISTS {$this->MAMPid}_CIload") or die($db->error);
    $db->query("DROP TABLE IF EXISTS {$this->MAMPid}_ci") or die($db->error);
    parent::delete();
  }

  public function commit($timestamp=true){
	  $old = $this->time;
	  parent::commit();

	  /* fulhack because there is no sane way to have BO preserve a timestamp with ON UPDATE */
	  if ( !$timestamp ){
		  $this->time = $old;
		  parent::commit();
	  }
  }
}

?>