<?php
require_once("../controller/controller.php");
$ctrl = new controller();

if (!empty($_POST['dele'])) {
	$ctrl->delData("articulos", $_POST['dele']);
	die;
}

// User have a new Picture or wants to preserve the previous.
if (!empty($_FILES['foto']['name'])) {
	$filename = $_FILES['foto']['name'];
	$error = $ctrl->uploadfoto();
} else
	$filename = $_POST['previous'];
	
if (empty($error))
{
	$titulo = $_POST['titulo'];
	$resumen = $_POST['descripcion'];
	$articulo = $_POST['articulo'];

	if (!empty($_POST['id_edit']))
		$id = $_POST['id_edit'];
	
	$fecha = $_POST['fecha'];
	$fields = "titulo, resumen, articulo, image, fecha";
	
	//If user is editing
	if (!empty($_POST['id_edit']))
		$error = $ctrl->editData("articulos", $id, $fields, 
											  $titulo, $resumen, 
											  $articulo, $filename, $fecha);
	else
		$error = $ctrl->addData("articulos", $titulo, $resumen, $articulo, $filename, $fecha);
}

echo $error;

die;
?>