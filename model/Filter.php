<?php

require_once('BasicObject.php');

class Filter extends BasicObject {
	static protected function table_name(){
		return 'filter';
	}

	static public function from_filter_id($mp, $filter_id){
		$all = static::selection(array('mp' => $mp->id, 'filter_id' => $filter_id, '@limit' => 1));
		return $all[0];
	}

	static private function next_id($mp){
		global $db;
		$id=$mp->id;
		$mampid = $mp->MAMPid;
		$stmt = $db->prepare("SELECT MAX(filter_id) FROM `filter` WHERE `mp` = ?");
		if ( !$stmt ) die($db->error);
		$stmt->bind_param('i', $id);
		$stmt->bind_result($id);
		$stmt->execute();
		if ( !$stmt->fetch() ){
			$stmt->close();
			return 10;
		}
		$stmt->close();

		/* 10-19 -> 20 etc */
		$id = (int)($id / 10);
		return ($id+1) * 10;
	}

	static public function placeholder($mp){
		$tmp = new Filter();
		$tmp->index = 0;
		$tmp->filter_id = static::next_id($mp);
		$tmp->mode = 'AND';
		$tmp->VLAN_TCI_MASK = '0xffff';
		$tmp->ETH_TYPE_MASK = '0xffff';
		$tmp->ETH_SRC = str_replace(':', '', $mp->mac);
		$tmp->ETH_SRC_MASK = 'ffffffffffff';
		$tmp->ETH_DST = str_replace(':', '', $mp->mac);
		$tmp->ETH_DST_MASK = 'ffffffffffff';
		$tmp->IP_SRC_MASK = '255.255.255.255';
		$tmp->IP_DST_MASK = '255.255.255.255';
		$tmp->SRC_PORT_MASK = '0xffff';
		$tmp->DST_PORT_MASK = '0xffff';
		$tmp->destaddr = '010000000001';
		$tmp->type = 1;
		$tmp->caplen = 54;
		return $tmp;
	}

	public function protocol(){
		return getprotobynumber($this->IP_PROTO);
	}

	/**
	 * Gives a plain-text description of the filter.
	 */
	public function description(){
		$index=$this->index;
		$parts = array();

		if($index&512){
			$parts[] = "(if = {$this->CI})";
		}
		if($index&256){
			$parts[] = "(vlantci = {$this->VLAN_TCI})";
		}
		if($index&128){
			$parts[] = "(ethtype = {$this->ETH_TYPE})";
		}
		if($index&64){
			$parts[] = "(ethsrc = {$this->ETH_SRC})";
		}
		if($index&32){
			$parts[] = "(ethdst = {$this->ETH_DST})";
		}
		if($index&16){
			$parts[] = "(IP proto = " . getprotobynumber($this->IP_PROTO) . ")";
		}
		if($index&8){
			$parts[] = "(IP src = {$this->IP_SRC})";
		}
		if($index&4){
			$parts[] = "(IP dst = {$this->IP_DST})";
		}
		if($index&2){
			$parts[] = "(SPORT = {$this->SRC_PORT})";
		}
		if($index&1){
			$parts[] = "(DPORT = {$this->DST_PORT})";
		}

		if($index==0){
			$parts[] = "Grab everything.";
		}

		$glue = $this->mode == 'AND' ? 'and' : 'or';
		return implode(" $glue ", $parts);
	}

	public function destination_description(){
		$destination = "";

		switch ( $this->type ){
		case 0:
			$destination = "Local to ";
			break;
		case 1:
			$destination = "Ethernet to \"0x";
			break;
		case 2:
			$destination = "UDP to ";
			break;
		case 3:
			$destination = "TCP to ";
			break;
		default:
			$destination = "Unknown to ";
			break;
		}

		$destination .= "{$this->destaddr}\" length {$this->caplen} bytes.";
		return $destination;
	}

	static public function validate_id($mp, $id){
		global $db;
		$mp_id = $mp->id;
		$stmt = $db->prepare("SELECT 1 FROM `filter` WHERE mp=? AND filter_id=? LIMIT 1") or die($db->error);
		$stmt->bind_param('ii', $mp_id, $id);
		$stmt->execute();
		return !$stmt->fetch();
	}
}

?>