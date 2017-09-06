<?php
require("curl.class.php");

$fTmpName = "";
$tmp_name = "";

// Make sure a file has been selected for upload 
if(is_uploaded_file($_FILES['file_any']['tmp_name'])){
	$fTmpName = $_FILES['file_any']['tmp_name'];
	$tmp_name = "@".$fTmpName;
}else{
	die("No such file selected.");
}

$options = array(
	"url" => "http://localhost/wArLeY_cURL/process_file.php",
	"data" => "plain",
	"post" => "1",
	"return_transfer" => "1",
	"post_fields" => array("nombre" => $_POST["txt_name"], "archivo" => $tmp_name),
	"redirect" => "0"
);

$obj = new wArLeY_cURL($options);
$resp = $obj->Execute();
//echo $obj->getError();

echo $resp;
?>