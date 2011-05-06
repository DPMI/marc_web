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
    if ( $filter_id == null ){
      $data['mps'] = array(MP::from_mampid($mampid));
      return template('filter/list.php', $data);
    }
  }
};

?>