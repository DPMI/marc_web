<?php

class HTTPRedirect extends Exception {
  public $url;

  public function __construct($url){
    parent::__construct();
    $this->url = $url;
  }
}

class HTTPError extends Exception {
  public $code;

  public function __construct($code){
    parent::__construct();
    $this->code = $code;
  }
}

class HTTPError403 extends HTTPError {
  public function __construct(){
    parent::__construct(403);
  }
}

class HTTPError404 extends HTTPError {
  public function __construct(){
    parent::__construct(404);
  }

}


class Controller {
  public function _path(array $path){
    $func = 'index';
    if ( count($path) > 0){
      $func = array_shift($path);
    }

    $func = array($this, $func);
    if ( !is_callable($func) ){
      throw new HTTPError404();
    }

    return call_user_func_array($func, $path);
  }

  protected function validate_access($level){
    global $u_access;
    if ( $u_access < $level ){
      throw new HTTPError403();
    }
  }
};

?>