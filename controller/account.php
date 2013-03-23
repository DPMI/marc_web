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
		global $u_id, $index;
		$id = (int)$_POST['id'];
		$error = array();

		if ( isset($_POST['cancel']) ){
			throw new HTTPRedirect("$index");
		}

		if ( $id == -1 ){ /* new account */
			parent::validate_access(2);

			if ( Account::from_username($_POST['uname']) != null ){
				$error[] = 'Username already taken.';
			}
			$account = new Account();
			$exist = false;
		} else if ( $id == $u_id ){ /* user edit */
			parent::validate_access(1);
			if ( isset($_POST['status']) ){ /* prevent spoof */
				parent::validate_access(2);
			}
			$account = Account::from_id($id);
			$exist = true;
		} else { /* admin edit */
			parent::validate_access(2);
			$account = Account::from_id($id);
			$exist = true;
		}

		/* validate password before making any changes */
		$p1 = $_POST['passwd-1'];
		$p2 = $_POST['passwd-2'];
		if ( strlen($p1) > 0 && ( $p1 != $p2 ) ){
			$error[] = 'Passwords does not match.';
		}

		$account->Name = $_POST['name'];
		$account->Email = $_POST['email'];

		if ( isset($_POST['status']) ){ /* admin edit */
			if ( $account->uname != $_POST['uname'] && strlen($p1) == 0 ){
				$error[] = 'When changing username, a new password must be set.';
			}

			$account->uname = $_POST['uname'];
			$account->status = $_POST['status'];
			$account->comment = $_POST['comment'];
		}

		/* show errors (fields are set first so it can fill in the fields in the form) */
		if ( count($error) > 0 ){
			if ( !$exist ){
				$account->id = -1; /* hard-coded id */
			}

			$data['account'] = $account;
			$data['exist'] = $exist;
			$data['admin'] = isset($_POST['status']);
			$data['error'] = $error;
			return template('account/view.php', $data);
		}

		$account->commit();

		/* The password cannot be set until a user id exists, so the account must
		 * be committed before setting it. */
		if ( strlen($p1) > 0 ){
			$account->set_password($p1);
			$account->commit();
		}

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

	function login(){
		$submit = isset($_GET['submit']);

		if ( $submit ){
			$uname=$_POST["uName"];
			$pass=$_POST["pWord"];

			$account = Account::validate_login($uname, $pass);
			if ( !$account ){
				return template('account/login.php', array('msg' => array('Invalid username or password.')));
			}

			$_SESSION["OK"]="OK";
			$_SESSION['user_id'] = $account->id;
			$_SESSION["accesslevel"] = $account->status;
			$_SESSION["username"] = $account->uname;
			if ( strlen($account->passwd) < 100 ){ /* 100 is just arbitrary, PASSWORD() hash is less than 100 at least. New password hashes are 128 bytes. */
				$_SESSION['passwd_warning'] = true;
			}

			global $root;
			$return = $root . 'index.php';
			if ( isset($_SESSION['return']) ){
				$return = $_SESSION['return'];
			}
			throw new HTTPRedirect($return);
		}

		return template('account/login.php', array());
	}

	function logout(){
		session_destroy();
		global $index;
		throw new HTTPRedirect($index);
	}
}

?>