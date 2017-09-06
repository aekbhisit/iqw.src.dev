<?php
require("curl.class.php");

$options = array(
	"url" => "http://localhost/wArLeY_cURL/process_page.php",
	"data" => "web",
	"data_filename" => "example_webpage.html",
	"post" => "1",
	"return_transfer" => "1",
	"post_fields" => array("nombre" => "Evert Ulises", "apellidos" => "German Soto")
);

$obj = new wArLeY_cURL($options);
$resp = $obj->Execute();
echo $obj->getError();

// You can redirect to open saved file or you can do anything...
header("location: ".$resp);
?>