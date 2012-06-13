<?php

class MPStatus {
  private $_time;

  private $_noFilters = array();
  private $_matchedPkts = array();
  private $_PKT0 = array();
  private $_BU0 = array();

  private $_dmatchedPkts = array();
  private $_dPKT0 = array();

  public function __construct($mampid, $limit=null){
    global $db;

    $sql = null;
    if ( $limit ){
      $sql = "
	SELECT
		*
	FROM (
		SELECT
			*
		FROM
			{$mampid}_CIload
		ORDER BY
		      time DESC
		LIMIT $limit
	) as t
	ORDER BY
	      t.time
	";
    } else {
      $sql = "SELECT * FROM {$mampid}_CIload ORDER BY time";
    }

    $result = $db->query($sql);
    if ( !$result ){
      die($db->error);
    }

    $n = 0;
    while ( ($row=$result->fetch_assoc()) ){
      $this->_noFilters[] = $row['noFilters'];
      $this->_matchedPkts[] = $row["matchedPkts"];
      $this->_PKT0[] = isset($row["PKT0"]) ? $row["PKT0"] : 0;
      $this->_BU0[] = isset($row["BU0"]) ? $row["BU0"] : 0;
      $this->_time = $row['time'];
      $n++;
    }

    /* calculate deltas */
    for ( $i=1; $i<$n; $i++){
      $this->_dmatchedPkts[] = max($this->_matchedPkts[$i] - $this->_matchedPkts[$i-1], 0);
      $this->_dPKT0[] = $this->_PKT0[$i] - $this->_PKT0[$i-1];
    }
  }

  public function time(){
    return $this->_time;
  }

  public function matched_pkts(){
    return $this->_dmatchedPkts;
  }

  public function last_matched_pkts(){
    return end($this->_dmatchedPkts);
  }

  public function max_delta_matched_pkts(){
    return count($this->_dmatchedPkts) > 0 ? max($this->_dmatchedPkts) : 0.0;
  }

  public function last_received_pkts(){
    return end($this->_dPKT0);
  }

  public function max_delta_received_pkts(){
    return count($this->_dPKT0) > 0 ? max($this->_dPKT0) : 0.0;
  }

  public function last_BU(){
    return end($this->_BU0);
  }

  public function max_BU(){
    return max($this->_BU0);
  }


}

?>