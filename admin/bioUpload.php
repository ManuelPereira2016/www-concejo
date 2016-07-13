<?php
require_once("../controller/controller.php");
$ctrl = new controller();

if (!empty($_POST['dele'])) {
	$ctrl->delData("biografias", $_POST['dele']);
	die;
}

// User have a new Picture or wants to preserve the previous.
if (!empty($_FILES['foto']['name'])) {
	$filename = $_FILES['foto']['name'];
	$error = $ctrl->uploadfoto();
}
elseif (!empty($_POST['previous']))
	$filename = $_POST['previous'];
	
if (empty($error))
{
	$nombre = $_POST['nombre'];
	$biografia = $_POST['biografia'];	
	$fecha = strftime("%A %d de %B del %Y");
	$fields = "nombre, biografia, image";
	
	// If user is editing
	if (!empty($_POST['id_edit'])) {
		$id = $_POST['id_edit'];
		$error = $ctrl->editData("biografias", $id, $fields, $nombre, $biografia, $filename);
	}
	else
		$error = $ctrl->addData("biografias", $nombre, $biografia, $filename, $fecha);
}

echo $error;
	
die;
?>
