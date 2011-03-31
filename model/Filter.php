<?

/**
 * Sadly there is no easy was to implement this with BasicObject as the table
 * name changes for each MP.
 */
class Filter {
  private $data;

  public function __construct($data){
    $this->data = $data;
  }

  public function __get($name){
    return $this->data[$name];
  }

  public function protocol(){
    return getprotobynumber($this->IP_PROTO);
  }

  /**
   * Gives a plain-text description of the filter.
   */
  public function description(){
    $index=$this->ind;
    $parts = array("($index)");
    
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
}

?>