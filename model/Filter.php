<?

/**
 * Sadly there is no easy was to implement this with BasicObject as the table
 * name changes for each MP.
 */
class Filter {
  private $mp;
  private $data;
  private static $columns = array(
	'filter_id', 'ind',
	'CI_ID',
	'VLAN_TCI', 'VLAN_TCI_MASK',
	'ETH_TYPE', 'ETH_TYPE_MASK',
	'ETH_SRC', 'ETH_SRC_MASK',
	'ETH_DST', 'ETH_DST_MASK',
	'IP_PROTO',
	'IP_SRC', 'IP_SRC_MASK',
	'IP_DST', 'IP_DST_MASK',
	'SRC_PORT', 'SRC_PORT_MASK',
	'DST_PORT', 'DST_PORT_MASK',
	'consumer',
	'DESTADDR', 'TYPE',
	'CAPLEN',
  );

  public function __construct($mp, array $data){
    $this->mp = $mp;
    foreach($data as $key => $value){
      $this->$key = $value;
    }
  }

  static public function placeholder($mp){
    $data = array_fill_keys(Filter::$columns, null);
    $data['ind'] = 0;
    $data['filter_id'] = 10;
    $data['VLAN_TCI_MASK'] = '0xffff';
    $data['ETH_TYPE_MASK'] = '0xffff';
    $data['ETH_SRC'] = str_replace(':', '', $mp->mac);
    $data['ETH_SRC_MASK'] = 'ffffffffffff';
    $data['ETH_DST'] = str_replace(':', '', $mp->mac);
    $data['ETH_DST_MASK'] = 'ffffffffffff';
    $data['IP_SRC_MASK'] = '255.255.255.255';
    $data['IP_DST_MASK'] = '255.255.255.255';
    $data['SRC_PORT_MASK'] = '0xffff';
    $data['DST_PORT_MASK'] = '0xffff';
    $data['DESTADDR'] = '010000000001';
    $data['TYPE'] = 1;
    $data['CAPLEN'] = 54;
    return new Filter($mp, $data);
  }

  public function __get($name){
    return $this->data[$name];
  }

  public function __set($key, $value){
    if ( !in_array($key, static::$columns) ){
      throw new Exception("filter has no column named $key");
    }
    $this->data[$key] = $value;
  }

  public function protocol(){
    return getprotobynumber($this->IP_PROTO);
  }

  /**
   * Gives a plain-text description of the filter.
   */
  public function description(){
    $index=$this->ind;
    $parts = array();

    if($index&512){
      $parts[] = "(if = {$this->CI_ID})";
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
      $parts[] = "(IP PROTO = " . getprotobynumber($this->IP_PROTO) . ")";
    }
    if($index&8){
      $parts[] = "(IP SRC = {$this->IP_SRC})";
    }
    if($index&4){
      $parts[] = "(IP DST = {$this->IP_DST})";
    }
    if($index&2){
      $parts[] = "(SPORT = {$this->SRC_PORT})";
    }
    if($index&1){
      $parts[] = "(DPORT = {$this->DST_PORT})";
    }

    return implode(" and ", $parts);
  }

  public function destination_description(){
    $destination = "";

    switch ( $this->TYPE ){
    case 0:
      $destination = "Local to ";
      break;
    case 1:
      $destination = "Ethernet to 0x";
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

    $destination .= "\"{$this->DESTADDR}\" length {$this->CAPLEN} bytes.";
    return $destination;
  }

  static private function sql_error($query, $error){
    throw new Exception("Failed to execute MySQL query: <b>$error</b>. The query was:\n<pre>\n$query\n</pre>");
  }

  public function validate_id($id){
    global $db;
    $query = "SELECT 1 FROM {$this->mp->filter_table()} WHERE filter_id = " . (int)$id . " LIMIT 1";
    $result = $db->query($query);
    if ( !$result ){
      $this->sql_error($query, $db->error);
    }
    return !$result || $result->num_rows == 0;
  }

  /**
   * Commit changes to a filter.
   * @param old_id If updating an existing filter, pass the filter id.
   */
  public function commit($old_id=null){
    global $db;

    $id = $old_id != null ? $old_id : $this->data['filter_id'];
    $types = str_repeat('s', count($this->data));
    $keys = array_map(create_function('$x', 'return "$x = ?";'), array_keys($this->data));
    $param = array(&$types);

    /* array_values gives values, need references */
    foreach ( $this->data as $key => $value ){
      $param[] = &$this->data[$key];
    }

    $sql = '';
    if ( $old_id == null ){
      $sql .= "INSERT INTO \n";
    } else {
      $sql .= "UPDATE \n";
    }

    $sql .= "\t{$this->mp->filter_table()}\nSET\n\t" . implode(", \n\t", $keys) . "\n";

    if ( $old_id != null ){
      $sql .= " WHERE filter_id = ?";
      $param[] = &$id;
      $types .=  'i';
    }

    $stmt = $db->prepare($sql);
    if ( !$stmt){
      throw new Exception($db->error);
    }

    call_user_func_array(array($stmt, 'bind_param'), $param);
    if ( !$stmt->execute() ){
      throw new Exception("Failed to execute MySQL query:\n<pre>\n$sql\n{$stmt->error}</pre>");
    }
    $stmt->close();
  }

  public function delete(){
    global $db;

    $db->query("DELETE FROM {$this->mp->filter_table()} WHERE filter_id = " . (int)$this->filter_id);
  }
}

?>