<?php

require('Controller.php');
require('model/account.php');

class AccountController extends Controller {
  public function index(){
    parent::validate_access(2);

    $order = isset($_GET['order']) ? $_GET['order'] : 'id';
    $asc = isset($_GET['asc']) ? (int)$_GET['asc'] : 1;

    $data['ascinv'] = 1 - $asc;
    $data['all'] = Account::selection(array(
      '@order' => "$order" . ($asc ? '' : ':desc')
    ));

    return template('account/list.php', $data);
  }
}

?>