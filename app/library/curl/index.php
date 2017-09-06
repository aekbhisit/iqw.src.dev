<?php
require("curl.class.php");

$options = array(
	"url" => "http://localhost/wArLeY_cURL/process_page.php",
	"data" => "plain",
	"post" => "1",
	"return_transfer" => "1",
	"post_fields" => array("nombre" => "Evert Ulises", "apellidos" => "German Soto")
);

$obj = new wArLeY_cURL($options);
$resp = $obj->Execute();
echo $obj->getError();

echo $resp;
?>