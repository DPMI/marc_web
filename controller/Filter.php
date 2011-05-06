<?php
require_once('Controller.php');
require_once('model/MP.php');

class FilterController extends Controller {
  public function index(){
    $data['mps'] = MP::selection();
    return template('filter/list.php', $data);
  }
};

?>