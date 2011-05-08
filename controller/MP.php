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
    
    $mp = MP::from_mampid($id);
    if ( !$mp ){
      return template('mp/invalid.php', array());
    }

    $data['mp'] = $mp;
    return template('mp/view.php', $data);
  }

  public function filter($mampid, $filter_id=null){
    parent::validate_access(1);

    $mp = MP::from_mampid($mampid);
    if ( !$mp ){
      return template('mp/invalid.php', array());
    }

    if ( $filter_id == null ){
      $data['mps'] = array($mp);
      return template('filter/list.php', $data);
    } else {
      $data['mp'] = $mp;
      $data['filter'] = $mp->filter($filter_id);
      $data['filter_exist'] = true;
      $data['errors'] = array();
      return template('filter/view.php', $data);
    }
  }

  public function filter_add($mampid){
    parent::validate_access(1);

    $mp = MP::from_mampid($mampid);
    if ( !$mp ){
      return template('mp/invalid.php', array());
    }

    $data['mp'] = $mp;
    $data['filter'] = Filter::placeholder($mp);
    $data['filter_exist'] = false;
    $data['errors'] = array();
    return template('filter/view.php', $data);
  }

  public function filter_update($mampid){
    parent::validate_access(1);
    global $index;

    $mp = MP::from_mampid($mampid);
    if ( !$mp ){
      return template('mp/invalid.php', array());
    }

    if ( isset($_POST['cancel']) ){
      throw new HTTPRedirect("$index/MP/view/{$mp->MAMPid}");
    }
    
    $FILTER_ID=$_POST["filter_id"];
    $OLD_FILTER_ID=$_POST["old_filter_id"];
    
    $fields = $_POST;
    unset($fields['old_filter_id']);
    unset($fields['mp']);
    unset($fields['action']);
    foreach ($fields as $key => $value){
      if ( strcmp(substr($key, -10), '_selection') == 0 || strcmp(substr($key, -3), '_cb') == 0 ){
	unset($fields[$key]);
      }
    }

    $filter = new Filter($mp, $fields);

    /* validate filter_id if it changes */
    if ( $FILTER_ID != $OLD_FILTER_ID ){
      if ( !$filter->validate_id($FILTER_ID) ){
	$data['mp'] = $mp;
	$data['filter'] = $filter;
	$data['filter_exist'] = $OLD_FILTER_ID > 0;
	$data['errors'][] = 'Filter ID is already used.';
	return template('filter/view.php', $data);
      }
    }

    $filter->commit($OLD_FILTER_ID > 0 ? $OLD_FILTER_ID : null);
    
    $mp->reload_filter($FILTER_ID);
    throw new HTTPRedirect("$index/MP/filter/{$mp->MAMPid}");
  }
};

?>