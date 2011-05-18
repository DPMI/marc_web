<?php

require_once('BasicObject.php');

class Account extends BasicObject {
  static protected function table_name(){
    return 'access';
  }

  public static function from_username($username){
    return static::from_field('uname', $username);
  }

  public static function placeholder(){
    return new Account(array('id' => -1));
  }
}

?>