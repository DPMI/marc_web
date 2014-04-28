<?php

require_once('BasicObject.php');

class Meta extends BasicObject {
  static protected function table_name(){
    return 'meta';
  }

  static public function get($key, $default=null){
	  $row = parent::from_field('key', $key);
	  if ( !$row ){
		  return $default;
	  }
	  return $row->value;
  }
}

?>