<?php

require_once('BasicObject.php');

class Page extends BasicObject {
  static protected function table_name(){
    return 'pages';
  }

  static public function from_url($url){
    if ( is_array($url) ){
      $url = implode('/', $url);
    }
    return parent::from_field('url', $url);
  }
}

?>
