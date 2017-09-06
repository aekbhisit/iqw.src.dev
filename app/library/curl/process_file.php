<?php
$res = "The file " . $_POST["nombre"] ."&nbsp;";

if(is_uploaded_file($_FILES['archivo']['tmp_name'])){
	$fName = $_FILES['archivo']['name'];
	$fTmpName = $_FILES['archivo']['tmp_name'];

	if(move_uploaded_file($_FILES['archivo']['tmp_name'], $_POST["nombre"])){
		$res .= "has been uploaded succesful!";
	}
}else{
	$res .= "has some bugs.";
}
echo $res;
?>