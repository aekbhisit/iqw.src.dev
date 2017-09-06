<?php
require("curl.class.php");

$options = array(
	"url" => "http://localhost/wArLeY_cURL/process_redir.php",
	"data" => "plain",
	"return_transfer" => "1",
	"redirect" => "1"
);

$obj = new wArLeY_cURL($options);
$resp = $obj->Execute();
echo $obj->getError();

echo $resp;
?>