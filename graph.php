<?php

require("sessionCheck.php");
require("config.php");
require('model/MP.php');

function error($size, $data){
	$im = imagecreate($size[0], $size[1]);
	$bg = imagecolorallocate($im, 255, 255, 255);
	$fg = imagecolorallocate($im, 0, 0, 0);
	$dy = 15;
	foreach ( $data as $i => $line ){
		$fontsize = $i == 0 ? 5 : 2;
		imagestring($im, $fontsize, 5,  $i*$dy+5, $line, $fg);
	}
	header('Content-type: image/png');
	imagepng($im);
	imagedestroy($im);
	exit;
}

function get_param($key, $default=null){
	return isset($_GET[$key]) ? $_GET[$key] : $default;
}

function clamp($value, $min, $max){
	return max(min($value, $max), $min);
}

class Graph {
	private $mp = null;
	private $ci = null;
	private $type = null;
	private $size = null;
	private $filebase = null;
	private $span = null;

	public function __construct($mampid, $type, $ci){
		$this->set_size(-1, -1); /* set default size, must always be first */
		$this->set_mp($mampid, $ci);
		$this->set_type($type);
	}

	public function set_size($width, $height){
		global $graph_aspect;
		$this->size = $this->calc_size($width, $height, $graph_aspect);
	}

	public function set_timespan($span, $start, $end){
		if ( $span !== false ){
			$this->span = array("end-$span", "now", $span);
		} else if ( $start === false ){
			error($this->size, array("Paramter error", "Either span or start must be given"));
		} else {
			$this->span = array($start, $end, false);
		}
	}

	public function render(){
		$filename = $this->cache_filename();
		if ( !$this->need_rebuild($filename) ){
			return array($filename, true);
		}

		switch ( $this->type ){
		case 'packets': $this->render_packets($filename); break;
		case 'bu': $this->render_BU($filename); break;
		}

		return array($filename, false);
	}

	private function set_type($type){
		if ( !in_array($type, array('packets', 'bu') ) ){
			error($this->size, array("Parameter error", "Missing or invalid graph type"));
		}
		$this->type = $type;
	}

	private function set_mp($mampid, $ci){
		$this->mp = MP::from_mampid($mampid) or error($this->size, array("Parameter error", "Missing or invalid MAMPid"));
		$this->filebase = $mampid;
		$this->ci = false;

		if ( $ci !== false ){
			$iface = explode(';', $this->mp->CI_iface);
			$x = $iface[$ci];
			$this->filebase = "{$mampid}_$x";
			$this->ci = $x;
		}

		$version = $this->mp->mp_version();
		$this->have_dropped = $version[2] >= 11;
		$this->have_bu = $version[2] >= 6;
	}

	private function calc_size($width, $height, $aspect){
		global $graph_width; /* from configuration */
		if ( $width == -1 && $height == -1 ){
			return array($graph_width, (int)($graph_width / $aspect));
		} else if ( $width == -1 && $height != -1 ){
			return array((int)($height * $aspect), $height);
		} else if ( $width != -1 && $height == -1 ){
			return array($width, (int)($width / $aspect));
		} else {
			return array($width, $height);
		}
	}

	private function title(){
		$timespan = $this->span[2] ? $this->span[2] : "{$this->span[0]} to {$this->span[1]}";
		if ( $this->ci === false ){
			return "{$this->mp->name} ($timespan)";
		} else {
			return "{$this->mp->name} {$this->ci} ($timespan)";
		}
	}

	public function pretty_filename(){
		return "{$this->filebase}_{$this->type}.png";
	}

	private function cache_filename(){
		global $cachedir;

		$parts = array($this->filebase, $this->type, $this->span[0], $this->span[1], $this->size[0], $this->size[1]);
		$hash = md5(implode('_', $parts));
		$path = $cachedir . substr($hash, 0, 2);
		$filename = substr($hash, 2);
		if ( !is_dir($path) ) mkdir($path);

		return $path . '/' . $filename;
	}

	private function need_rebuild($filename){
		global $cache, $graph_max_age;
		if ( !$cache ){
			return true;
		}

		return time() - $this->last_modified() > $graph_max_age;
	}

	public function last_modified(){
		$filename = $this->cache_filename();
		$stat = @stat($filename);
		if ( $stat == false ) return 0;
		return $stat['mtime'];
	}

	private function rrdtool_common($filename){
		return array(
			"rrdtool", "graph", $filename,
			"-a", "PNG",
			"--full-size-mode", "--width", escapeshellarg($this->size[0]), "--height", escapeshellarg($this->size[1]),
			"--title", escapeshellarg($this->title()),
			"--start", escapeshellarg($this->span[0]), "--end", escapeshellarg($this->span[1]),
		);
	}

	private function render_packets($filename){
		global $rrdbase;
		$filebase = $this->filebase;

		$common = $this->rrdtool_common($filename);
		$argv = array_merge($common, array(
			                    "--vertical-label", "pkt/sec",
			                    "'DEF:total=$rrdbase/$filebase.rrd:total:AVERAGE'",     "'VDEF:total_last=total,TOTAL'",
			                    "'DEF:matched=$rrdbase/$filebase.rrd:matched:AVERAGE'", "'VDEF:matched_last=matched,TOTAL'",
			                    "'DEF:dropped=$rrdbase/$filebase.rrd:dropped:AVERAGE'", "'VDEF:dropped_last=dropped,TOTAL'",
			                    "'CDEF:discarded=total,matched,-,dropped,-'", "'VDEF:discarded_last=discarded,TOTAL'",
			                    "'AREA:dropped#ff0000:Dropped\:   :'",        $this->have_dropped ? "'GPRINT:dropped_last:%12.0lf pkts\l'" : "'COMMENT:              N/A\l'",
			                    "'AREA:discarded#ffff00:Discarded\: :STACK'", "'GPRINT:discarded_last:%12.0lf pkts\l'",
			                    "'AREA:matched#00ff00:Matched\:   :STACK'",   "'GPRINT:matched_last:%12.0lf pkts\l'",
			                    "'LINE1:total#000000:Total\:     :'",         "'GPRINT:total_last:%12.0lf pkts\l'",));
		$this->exec($argv);
	}

	private function render_bu($filename){
		global $rrdbase;
		$filebase = $this->filebase;

		$argv = $this->rrdtool_common($filename);
		$argv = array_merge($argv, array(
			                    "--vertical-label", "'Utilization (%)'",
		                      "-l", "0", "-u", "100",
		                      "DEF:BU=$rrdbase/$filebase.rrd:BU:MAX",
		                      "VDEF:BU_last=BU,LAST",
		                      "VDEF:BU_min=BU,MINIMUM",
		                      "VDEF:BU_max=BU,MAXIMUM",
		                      "VDEF:BU_avg=BU,AVERAGE",
		                      "VDEF:BU_95=BU,95,PERCENTNAN",

		                      "CDEF:bue=BU,00,LE,BU,00,IF",
		                      "CDEF:bu0=BU,00,GT,BU,10,GT,10,BU,00,-,IF,UNKN,IF",
		                      "CDEF:bu1=BU,10,GT,BU,20,GT,10,BU,10,-,IF,UNKN,IF",
		                      "CDEF:bu2=BU,20,GT,BU,30,GT,10,BU,20,-,IF,UNKN,IF",
		                      "CDEF:bu3=BU,30,GT,BU,40,GT,10,BU,30,-,IF,UNKN,IF",
		                      "CDEF:bu4=BU,40,GT,BU,50,GT,10,BU,40,-,IF,UNKN,IF",
		                      "CDEF:bu5=BU,50,GT,BU,60,GT,10,BU,50,-,IF,UNKN,IF",
		                      "CDEF:bu6=BU,60,GT,BU,70,GT,10,BU,60,-,IF,UNKN,IF",
		                      "CDEF:bu7=BU,70,GT,BU,80,GT,10,BU,70,-,IF,UNKN,IF",
		                      "CDEF:bu8=BU,80,GT,BU,90,GT,10,BU,80,-,IF,UNKN,IF",
		                      "CDEF:bu9=BU,90,GT,BU,90,-,UNKN,IF",

		                      "AREA:bu0#00ff00::STACK",
		                      "AREA:bu1#19e100::STACK",
		                      "AREA:bu2#32af00::STACK",
		                      "AREA:bu3#4b9600::STACK",
		                      "AREA:bu4#647d00::STACK",
		                      "AREA:bu5#7d6400::STACK",
		                      "AREA:bu6#964b00::STACK",
		                      "AREA:bu7#af3200::STACK",
		                      "AREA:bu8#e11900::STACK",
		                      "AREA:bu9#ff0000::STACK",
		                      "LINE2:BU_95#00000055:",

			                    "'COMMENT:Buffer utilization '", $this->have_bu ? "'GPRINT:BU_last:%3.1lf%%\l'" : "'COMMENT: N/A\l'"),
		                    ($this->have_bu ? array(
			                    "'COMMENT:95th percentile    '", $this->have_bu ? "'GPRINT:BU_95:%3.1lf%%\l'"   : "'COMMENT: N/A\l'",
		                      "'COMMENT:Min'", "'GPRINT:BU_min:%3.1lf%%'",
		                      "'COMMENT:Max'", "'GPRINT:BU_max:%3.1lf%%'",
			                    "'COMMENT:Avg'", "'GPRINT:BU_avg:%3.1lf%%\l'") : array()));
		$this->exec($argv);
	}

	private function exec($argv){
		$cmd = implode(' ', $argv);
		exec("$cmd 2>&1", $output, $rc);
		if ( $rc != 0 ){
			$chars = floor($this->size[0] / imagefontwidth(2));
			error($this->size, array_merge(
				      array("RRDtool error code $rc"),
				      explode("\n", wordwrap($cmd, $chars, "\\\n", true)),
				      array("Output:"),
				      explode("\n", wordwrap(implode("\n", $output), $chars, "\\\n", true))));
		}
	}
};

/* get parameters */
$mampid = get_param('mampid');
$what = get_param('what');
$ci = get_param('CI', false);
$span = get_param('span', false);
$start = get_param('start', false);
$end = get_param('end', "now");
$cache = get_param('cache', 1) == 1;
$width = clamp(get_param('width', -1), -1, 2000);
$height = clamp(get_param('height', -1), -1, 2000);

/* create graph */
$graph = new Graph($mampid, $what, $ci);
$graph->set_size($width, $height);
$graph->set_timespan($span, $start, $end);

$orig_mtime = $graph->last_modified();
list($filename, $fresh) = $graph->render();
clearstatcache();

/* caching */
if ( $cache ){
	/* need to set default timezone because strtotime fails to parse the timezone, e.g strtotime(date('...', $x)) is not $x if TZ != GMT */
	date_default_timezone_set('UTC');

	$mtime = $graph->last_modified();
	header("Cache-Control: public, max-age={$graph_max_age}, must-revalidate");
	header('Last-Modified: ' . date('D, d M Y H:i:s', $mtime) . ' GMT');
	header('Expires: ' . date('D, d M Y H:i:s', $mtime + $graph_max_age) . ' GMT');
	header('X-GraphFresh: ' . ($fresh ? "yes" : "no"));

	if ( isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ){
		$last_modified = preg_replace('/;.*$/', '', $_SERVER['HTTP_IF_MODIFIED_SINCE']); /* not sure why but stackoverflow insists */
		if ( $fresh && $orig_mtime <= strtotime($last_modified) ){
			header("HTTP/1.0 304 Not Modified");
			exit;
		}
	}
}

header("Content-Disposition: inline; filename=\"{$graph->pretty_filename()}\"");
header("Content-type: image/png");
echo file_get_contents($filename);
if ( !$cache ) unlink($filename);
