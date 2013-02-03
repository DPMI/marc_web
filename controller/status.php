<?php

require_once('Controller.php');
require_once('model/Meta.php');

function check_path($path, $mode='r', &$message, $owner=null){
	$class = 'ok';
	$message = null;
	foreach ( str_split($mode) as $perm ){
		if ( $message != null ) break;
		switch ( $perm ){
		case 'r':
			if ( !is_readable($path) ){
				$class = 'error';
				$message = 'file not found';
			}
			break;
		case 'w':
			if ( !is_writable($path) ){
				$class = 'error';
				$message = 'file not writable';
			}
			break;
		case 'x':
			if ( !is_executable($path) ){
				$class = 'error';
				$message = 'file not executable';
			}
			break;
		case 'd':
			if ( !is_dir($path) ){
				$class = 'error';
				$message = 'not a directory';
			}
			break;
		default:
			trigger_error("Invalid mode $perm, ignored", E_WARNING);
		}
	}
	if ( !$message && isset($owner['gid']) && filegroup($path) != $owner['gid'] ){
    $cur = posix_getgrgid(filegroup($path));
		$class = 'error';
		$message = "the owning group ({$cur['name']}) does not correspond to selected group ({$owner['name']}).";
	}
	return "<span class=\"$class\">$path</span>";
}

function check_group($name, &$message){
	$message = null;
	$group = posix_getgrnam($name);
	if ( !$group ){
		$message = 'No such group';
		return "<span class=\"error\">$name</span>";
	}
  if ( !in_array($group['gid'], posix_getgroups()) ){
	  $cur =  posix_getpwuid(posix_getuid());
	  $message = 'User ' . $cur['name'] . ' is not a member of the group';
	  return "<span class=\"error\">$name</span>";
  }
	return "<span class=\"ok\">$name</span>";
}

class StatusController extends Controller {
	public function index(){
		parent::validate_access(2);

		$vcs = '';
		if ( file_exists('.git') ){
			$rev = trim(`git rev-parse --short HEAD`);
			$ref = trim(`git rev-parse --abbrev-ref HEAD`);
			if ( $rev && $ref ){
				$vcs = "$rev/$ref";
			}
		}

		$marcd = array(
			'version' => Meta::get('marcd_version'),
			'vcs' => Meta::get('marcd_vcs'),
			'caputils' => Meta::get('marcd_caputils'),
		);

		return template('status/index.php', $GLOBALS + array('vcs' => $vcs, 'marcd' => $marcd));
	}
}
