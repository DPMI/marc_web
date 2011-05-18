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

  public function set_password($str){
    $this->passwd = $this->hash($str, $this->uname);
  }

  /**
   * Validate a user login.
   * @return Account object or null if the credentials was invalid.
   */
  public static function validate_login($username, $password){
    /* yes it is a complete hazard but old passwords are stored using the mysql
     * function PASSWORD, and to remain compatable it accepts the password if it
     * matches either the salted hash or the old PASSWORD(). */
    /** @todo For the next release, drop this compatability. */

    $hash = Account::hash($password, $username);
    $result = parent::selection(array(
      'uname' => $username,
      '@or' => array(
        'passwd' => $hash,
	'@manual_query' => "passwd = PASSWORD('$password')"
      ),
      '@limit' => 1));

    if ( count($result) == 0 ){
      return null;
    } else {
      return $result[0];
    }
  }

  private static function salt($username){
    return "{$username}_troll";
  }

  private static function hash($str, $username){
    global $site_key;
    $salt = Account::salt($username);
    return hash_hmac('sha512', "$salt $str", $site_key);
  }
}

?>