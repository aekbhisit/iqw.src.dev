<?php
error_reporting(E_ERROR); 
ini_set('display_errors', 1);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
//load configulation
require_once("config.inc.php");
//----------------load class------------------------
require_once('classes/database.php');
// include function core
require_once("functions.inc.php");
//loadmodule
$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
$protocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]), 0, strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")) . $s;
$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
//$ROOT = $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . dirname($_SERVER['PHP_SELF']).DIRECTORY_SEPARATOR;
$ROOT = $protocol . "://" . $_SERVER['SERVER_NAME'] . $port.DIRECTORY_SEPARATOR;
$DOMAIN = $_SERVER['SERVER_NAME'];
if(!defined('ROOT')){
	define('ROOT',$ROOT);
}
if(!defined('DOMAIN')){
	define('DOMAIN',$DOMAIN);
}
if(isset($_GET['module'])){
	$module = $_GET["module"];
	if($module!='users'){
		require_once('module/users/class.php'); 
	}
	if(is_file("app/module/".$module."/init.php")||is_file("module/".$module."/init.php")){
		require_once("module/".$module."/init.php");
		require_once('classes/helper.php');
	}
	if(is_file("app/module/".$module."/class.php")||is_file("module/".$module."/class.php")){
		require_once("module/".$module."/class.php"); 
	}
	if(is_file("app/module/".$module."/controller.php")||is_file("module/".$module."/controller.php")){
		include("module/".$module."/controller.php"); 
	}
}

?>