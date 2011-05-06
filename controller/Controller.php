<?php

class HTTPError extends Exception {

}

class HTTPError404 extends HTTPError {

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
};

?>