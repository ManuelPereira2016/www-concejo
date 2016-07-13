<?php
require_once("../controller/controller.php");
$ctrl = new controller();

$filename = "no.image.png";

// User have a new Picture or wants to preserve the previous.
if (!empty($_FILES['foto']['name'])) {
	$filename = $_FILES['foto']['name'];
	$error = $ctrl->uploadfoto();
}
elseif (!empty($_POST['previous']))
	$filename = $_POST['previous'];
	
$nombre = $_POST['nombre'];
$cargo = $_POST['cargo'];
$fields = "nombre, cargo, image";

if (!empty($_POST['id_edit']))
	$id = $_POST['id_edit'];

$error = $ctrl->editData("junta", $id, $fields, $nombre, $cargo, $filename);

echo $error;
	
die;
?>



