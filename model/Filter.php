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
}

?>