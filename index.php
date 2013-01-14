<?php

require("sessionCheck.php");
require("config.php");
require_once('model/Menu.php');

$menu = Menu::selection(array('accesslevel:<=' => $u_access, 'type:!=' => 3));

function template($view, $data){
	global $root, $index, $ajax;
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

/* for returning to previous page when logging in */
if ( $handler != 'account' ){
	$_SESSION['return'] = $_SERVER['PHP_SELF'] . '?'. $_SERVER['QUERY_STRING'];
}

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
    <link rel="stylesheet" type="text/css" href="<?=$root?>css/style.css" />
    <link rel="stylesheet" type="text/css" href="<?=$root?>css/jquery-ui-1.9.2.custom.min.css" />
    <link rel="shortcut icon" type="image/x-icon" href="http://www.bth.se/favicon.ico" />
    <script type="text/javascript" src="<?=$root?>js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="<?=$root?>js/jquery-ui-1.9.2.custom.min.js"></script>
    <script type="text/javascript" src="<?=$root?>js/jquery.jeditable.mini.js"></script>
    <script type="text/javascript" src="<?=$root?>js/filter.js"></script>
    <title><?=$title?> -- MArC</title>
  </head>

  <body>

	  <div id="dialog-confirm" title="" style="display: none;">
		  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><span class="text"></span></p>
	  </div>

    <div id="header">
      <map name="link">
	<area shape="circle" coords="25, 20, 18" href="http://www.bth.se/eng/">
	<area shape="rect" coords="600, 20, 700, 41" href="http://www.bth.se/eng/">
      </map>
      <img src="<?=$root?>lighthuvud.jpg" alt="" width="700" height="41" usemap="#link" border="0" />
      <img src="<?=$root?>header_image.php" width="700" height="59" alt="Internet Next Generation Analysis - <?=$projectName;?>" border="0" />
    </div>

    <div id="menu">
<? if ( isset($subtitle) ){ ?>
      <h1><?=$subtitle?></h1>
<? } ?>
      <h1>MArC - Member</h1>
      <ul>
        <li><a href="<?=$index?>">Home</a></li>
<?php foreach($menu as $item){ ?>
        <li><a href="<?=$item->href()?>"><?=$item->string?></a></li>
<?php } ?>
<?php if ( $u_access >= 1 ) { ?>
        <li><a href="<?=$index?>/MP">List MPs</a></li>
<?php } ?>
        <li><a href="<?=$index?>/FilterReadable">List filters</a></li>
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
	<li><a href="<?=$index?>/account">List Accounts</a></li>
	<li><a href="<?=$index?>/account/add">Add Account</a></li>
      </ul>
<?php } /* if $u_access > 1 */ ?>

      <h1>User</h1>
      <ul>
<?php if ( $u_id > 0 ){ ?>
	<li><a href="<?=$index?>/account/self">Account</a></li>
	<li><a href="<?=$index?>/account/logout">Logout</a></li>
<?php } else { ?>
	<li><a href="<?=$index?>/account/login">Login</a></li>
<?php } ?>
      </ul>

		 <p style="padding-left: 1em;">
			 Server time:<br/>
			 <?=gmstrftime("%d-%b-%y %T %z", time ())?><br/>
			 <?=strftime("%d-%b-%y %T %z", time ())?>
		 </p>
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
