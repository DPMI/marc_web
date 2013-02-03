<?php

require_once('Controller.php');
require_once('model/Meta.php');

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
