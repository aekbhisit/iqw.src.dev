<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// exit;

header('Content-Type: text/html; charset=utf-8');

// get Current Url
    $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
    $protocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]), 0, strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")) . $s;
    $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
    // $ROOT = $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . dirname($_SERVER['PHP_SELF']).DIRECTORY_SEPARATOR;
	$ROOT = $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . dirname($_SERVER['PHP_SELF']);
	define('ROOT', $ROOT );
// include theme 
	if(is_file('install/index.php')){
		 header('Location: install/index.php');
	}else{
		//$theme_name = 'bangkokrama' ;


		$theme_name = 'onetouch' ;
		define("THEME_ROOT",'themes/'.$theme_name.'/');
		define("THEME_ROOT_URL",$ROOT.'themes/'.$theme_name.'/');
		include('app/functions.inc.php');
		if(file_exists('app/route.php')){
			include('app/route.php'); 
		}else{
			include(THEME_ROOT.'load_theme.php'); 
		}
	}
?>