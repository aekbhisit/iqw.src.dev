<?php
error_reporting(E_ERROR);
ini_set('display_errors', 0);
header('Content-Type: text/html; charset=utf-8');   
session_start();
if(empty($_SESSION['userLogin'])){
	header( 'Location:../index.php' ) ;
}

// support peroid
$_SUPPORT_START = "2016-09-25 13:26:00";
$_SUPPORT_EXPIRE = "2016-09-25 13:26:00";

?>