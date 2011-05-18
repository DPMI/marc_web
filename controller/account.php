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

  public function add(){
    parent::validate_access(2);

    $data['account'] = Account::placeholder();
    $data['exist'] = false;
    $data['admin'] = true;
    return template('account/view.php', $data);
  }

  public function edit($id){
    parent::validate_access(2);

    $data['account'] = Account::from_id($id);
    $data['exist'] = true;
    $data['admin'] = true;
    return template('account/view.php', $data);
  }

  public function self(){
    parent::validate_access(1);

    global $u_id;
    $data['account'] = Account::from_id($u_id);
    $data['exist'] = true;
    $data['admin'] = false;
    return template('account/view.php', $data);
  }

  public function submit(){
    $id = (int)$_POST['id'];

    if ( $id == -1 ){ /* new account */
      parent::validate_access(2);
      $account = new Account();
    } else if ( $id == $u_id ){ /* user edit */
      parent::validate_access(1);
      if ( isset($_POST['status']) ){ /* prevent spoof */
	throw new HTTPError403();
      }
      $account = Account::from_id($id);
    } else { /* admin edit */
      parent::validate_access(2);
      $account = Account::from_id($id);
    }

    $account->uname = $_POST['uname'];
    $account->Name = $_POST['name'];
    $account->Email = $_POST['email'];

    $p1 = $_POST['passwd-1'];
    $p2 = $_POST['passwd-2'];
    if ( strlen($p1) > 0 && $p1 == $p2 ){
      $account->passwd = $p1;
    }

    if ( isset($_POST['status']) ){ /* admin edit */
      $account->status = $_POST['status'];
	$account->comment = $_POST['comment'];
    }
    
    $account->commit();
    return "<h1>Account saved</h1>";
  }

  function del($id){
    global $index;
    parent::validate_access(2);

    $account = Account::from_id($id);

    if ( isset($_GET['confirm']) ){
      $confirm = $_GET['confirm'];
      if ( $confirm == 'delete' ){
        $account->delete();
      }

      throw new HTTPRedirect("$index/account");
    }

    return confirm("Are you sure you want to delete account \"{$account->uname}\"?", array("delete", "cancel"));
  }
}

?>