<?php

require_once('Controller.php');
require_once('model/MP.php');

class MPController extends Controller {
  public function index(){
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
    $data['mp'] = MP::from_id($id);
    return template('mp/view.php', $data);
  }
};

?>