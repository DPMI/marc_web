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
		global $graph_max_age, $graph_packet_span, $graph_bu_span;
		parent::validate_access(1);

		$mp = MP::from_mampid($id);
		if ( !$mp ){
			return template('mp/invalid.php', array());
		}

		$data['mp'] = $mp;
		$data['interval'] = $graph_max_age;
		$data['packet_span'] = $graph_packet_span;
		$data['bu_span'] = $graph_bu_span;
		return template('mp/view.php', $data);
	}

	public function comment($mampid){
		parent::validate_access(1);
		$mp = MP::from_mampid($mampid);
		if ( !$mp ) return "no such mp";

		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ){
			$old_time = $mp->time;
			$mp->comment = htmlentities($_POST['value']);
			$mp->commit(false);
		}

		return $mp->comment;
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
			$data['filter'] = $mp->filter_by_id($filter_id);
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

	public function filter_del($mampid, $filter_id){
		parent::validate_access(1);
		global $index;

		$mp = MP::from_mampid($mampid);
		if ( !$mp ){
			return template('mp/invalid.php', array());
		}

		$filter = $mp->filter_by_id($filter_id);
		if ( !$filter ){
			return "Invalid filter";
		}

		if ( isset($_GET['confirm']) ){
			$confirm = $_GET['confirm'];
			if ( $confirm == 'delete' ){
				$filter->delete();
				$mp->del_filter($filter->filter_id);
			}

			throw new HTTPRedirect("$index/MP/filter/{$mp->MAMPid}");
		}

		return confirm("Are you sure you want to delete filter {{$filter->filter_id}} from {$mp->name}?", array("delete", "cancel"));
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

		$fields = $_POST;
		unset($fields['old_filter_id']);
		unset($fields['mp']);
		unset($fields['action']);

		/* special case for discard with isn't a real type */
		if ( $fields['type'] == '4' ){
			$fields['type'] = 0;
			$fields['destaddr'] = '/dev/null';
		}

		$filter_id=$_POST["filter_id"];
		$old_filter_id=$_POST["old_filter_id"];
		$filter = $old_filter_id >= 0 ? Filter::from_filter_id($mp, $old_filter_id) : new Filter();
		$filter->mp = $mp->id;

		foreach ($fields as $key => $value){
			if ( strcmp(substr($key, -10), '_selection') == 0 || strcmp(substr($key, -3), '_cb') == 0 ){
				unset($fields[$key]);
				continue;
			}
			$filter->$key = $value;
		}

		/* validate filter_id if it changes */
		if ( $filter_id != $old_filter_id ){
			if ( !$filter->validate_id($mp, $filter_id) ){
				$data['mp'] = $mp;
				$data['filter'] = $filter;
				$data['filter_exist'] = $old_filter_id > 0;
				$data['errors'][] = 'Filter ID is already used.';
				return template('filter/view.php', $data);
			}
		}

		$filter->commit();
		$mp->reload_filter($filter->filter_id);
		throw new HTTPRedirect("$index/MP/filter/{$mp->MAMPid}");
	}

	public function delete($id){
		parent::validate_access(1);
		global $index;

		$mp = MP::from_id($id);
		if ( !$mp ){
			return template('mp/invalid.php', array());
		}

		if ( isset($_GET['confirm']) ){
			$confirm = $_GET['confirm'];
			if ( $confirm == 'remove' ){
				$mp->delete();
			}

			throw new HTTPRedirect("$index/MP");
		}

		return confirm("Are you sure you want to remove measurement point \"{$mp->name}\" and all its data?<br/>This action is irreversable.", array("remove", "cancel"));
	}

	public function stop($id){
		parent::validate_access(1);
		global $index;

		$mp = MP::from_id($id);
		if ( !$mp ){
			return template('mp/invalid.php', array());
		}

		if ( isset($_GET['confirm']) ){
			$confirm = $_GET['confirm'];
			if ( $confirm == 'stop' ){
				$mp->stop();
				sleep(1); /* try to wait for it to stop before returning */
			}

			throw new HTTPRedirect("$index/MP");
		}

		return confirm("Are you sure you want to stop the measurement point \"{$mp->name}\"? It is not possible to restart it using the webgui.", array("stop", "cancel"));
	}
};

?>