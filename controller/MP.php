<?php

require_once('Controller.php');
require_once('model/MP.php');

class MPController extends Controller {
  public function index(){
    parent::validate_access(1);

    $order = isset($_GET['order']) ? $_GET['order'] : 'name';
    $asc = isset($_GET['asc']) ? (int)$_GET['asc'] : 1;
    $ascinv = 1 - $asc;
    $toggle=0;

    $mps = MP::selection(array(
      '@order' => "$order" . ($asc ? '' : ':desc')
    ));

    return template('mp/list.php', array('mps' => $mps, 'ascinv' => $ascinv));
  }

  public function view($id){
    parent::validate_access(1);

    $data['mp'] = MP::from_mampid($id);
    return template('mp/view.php', $data);
  }

  public function filter($mampid, $filter_id=null){
    trigger_error("No accesslevel specified");

    $mp = MP::from_mampid($mampid);

    if ( $filter_id == null ){
      $data['mps'] = array($mp);
      return template('filter/list.php', $data);
    } else {
      $data['mp'] = $mp;
      $data['filter'] = $mp->filter($filter_id);
      $data['filter_exist'] = true;
      return template('filter/view.php', $data);
    }
  }

  public function filter_add($mampid){
    trigger_error("No accesslevel specified");

    $data['mp'] = MP::from_mampid($mampid);
    $data['filter'] = Filter::placeholder($mp);
    $data['filter_exist'] = false;
    return template('filter/view.php', $data);
  }

  public function filter_update($mampid){
    trigger_error("No accesslevel specified");

    $mp = MP::from_mampid($mampid);
    if ( !$mp ){
      throw new Exception("No such MP");
    }

    if ( isset($_POST['cancel']) ){
      global $index;
      throw new HTTPRedirect("$index/MP/view/{$mp->MAMPid}");
    }
  }
};

?>