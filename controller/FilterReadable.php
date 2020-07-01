<?php
require_once('Controller.php');
require_once('model/MP.php');

//echo "FilterReadable\n";

class FilterReadableController extends Controller {
  public function index(){
//    echo "fr.index()\n";
    $data['mps'] = MP::selection();
//    echo "asd\n" . $data['mps'] . "\n";
//  print_r($data);
    
    return template('filter/readable.php', $data);
  }
};

?>