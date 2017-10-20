<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');
// get Current Url
$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
$protocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]), 0, strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")) . $s;
$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
// $ROOT = $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . dirname($_SERVER['PHP_SELF']).DIRECTORY_SEPARATOR;
$ROOT = $protocol."://".$_SERVER['SERVER_NAME'].$port.dirname($_SERVER['PHP_SELF']);
define('ROOT',$ROOT);
// include theme 
if(is_file('install/index.php')){
	header('Location: install/index.php');
}else{
	include('app/functions.inc.php');
	if ( is_session_started() === FALSE ) { session_start(); }
	if(file_exists('app/route.php')){
		include('app/route.php');
	}else{
		include(THEME_ROOT.'load_theme.php'); 
	}
}
?>