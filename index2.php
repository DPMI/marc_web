<?php

require("sessionCheck.php");
require("config.inc");
require_once('model/Menu.php');

$menu = Menu::selection(array('accesslevel:<=' => $u_access, 'type:!=' => 3));

function template($view, $data){
  global $root, $index;
  extract($data);
  ob_start();
  require("view/$view");
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}

function confirm($message, $alt){
  return template('confirm.php', array('message' => $message, 'alt' => $alt));
}

$path = array('');
if ( isset($_SERVER['PATH_INFO']) ){
  $path = explode('/', rtrim($_SERVER['PATH_INFO'],'/'));
  array_shift($path);
}
$handler = array_shift($path);

$controller = null;
$content = null;
if ( $handler == '' ){
  $content = template('welcome.php', array());
} else if ( file_exists("controller/$handler.php") ){
  require("controller/$handler.php");
  $classname = "{$handler}Controller";
  $controller = new $classname();
  
  try {
    $content = $controller->_path($path);
  } catch ( HTTPError403 $e ){
    $content = template('403.php', array());
  } catch ( HTTPError404 $e ){
    $content = template('404.php', array());
  } catch ( HTTPRedirect $e ){
    header("Location: {$e->url}");
    exit;
  } catch( Exception $e ){
    $content = template('exception.php', array('exception' => $e));
  }
  
} else {
  $content = template('404.php', array());
}


?>
<!DOCTYPE html>
<html lang="en-US" xml:lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="<?=$root?>style2.css" />
    <link rel="shortcut icon" type="image/x-icon" href="http://www.bth.se/favicon.ico" />
    <script type="text/javascript" src="<?=$root?>js/filter.js"></script>
    <title>Network Performance Lab -- MArC</title>
  </head>
  
  <body>

    <div id="header">
      <map name="link">
	<area shape="circle" coords="25, 20, 18" href="http://www.bth.se/eng/">
	<area shape="rect" coords="600, 20, 700, 41" href="http://www.bth.se/eng/">
      </map>
      <img src="<?=$root?>lighthuvud.jpg" alt="" width="700" height="41" usemap="#link" border="0" />
      <img src="<?=$root?>header_image.php" width="700" height="59" alt="Internet Next Generation Analysis - <?=$projectName;?>" border="0" />
    </div>

    <div id="menu">
      <h1>MArC - Member</h1>
      <ul>
        <li><a href="<?=$root?>index2.php">Home</a></li>
<?php foreach($menu as $item){ ?>
        <li><a href="<?=$root?><?=$item->href()?>"><?=$item->string?></a></li>
<?php } ?>
      </ul>

<?php if ( $u_id > 0 ){ ?>
      <h1>Site maintenance</h1>
      <ul>
	<li><a href="listPages.php">List Pages</a></li>
	<li><a href="uploadscript.php">Upload File</a></li>
      </ul>
<?php } ?>

<?php if ( $u_access > 1 ) { ?>
      <h1>Site administration</h1>
      <ul>
	<li><a href="addPage.php">Add Page</a></li>
	<li><a href="listGUIconfig.php">List GUI config</a></li>
	<li><a href="addGUI.php">Add GUI config</a></li>
	<li><a href="listMenu.php">List Menu</a></li>
	<li><a href="addMenu.php">Add Menu Entry</a></li>
	<li><a href="listAccounts.php">List Accounts</a></li>
	<li><a href="addAccount.php">Add Account</a></li>
      </ul>
<?php } /* if $u_access > 1 */ ?>

      <h1>User</h1>
      <ul>
<?php if ( $u_id > 0 ){ ?>
	<li><a href="<?=$root?>logout.php">Logout</a></li>
<?php } else { ?>
	<li><a href="<?=$root?>login.php">Login</a></li>
<?php } ?>
      </ul>

    </div>

    <div id="content">
<?=$content?>
    </div>

    <div id="footer">
      <hr/>
      <p>MArCd webgui - <?=$version?></p>
      <p>Maintained by <a href="mailto:pal@bth.se">Patrik Arlos</a>.</p>
      <p><a href="http://www.bth.se/">Blekinge Institute of Technology</a>.</p>
    </div>

  </body>

</html>
