<?php
require("../controller/controller.php");
$ctrl = new controller();

if (!empty($_POST['dele'])) {
	$ctrl->delData("biblioteca", $_POST['dele']);
	die;
}

if (!empty($_POST['del_previous']))
		$error = $ctrl->borrarDoc($_POST['del_previous']);
	
// User have a new Picture or wants to preserve the previous.
if (!empty($_FILES['doc']['name'])) {
	$filename = $_FILES['doc']['name'];
	$error = $ctrl->uploadfile();
} 
elseif (!empty($_POST['previous_doc']))
	$filename = $_POST['previous_doc'];
	
if (empty($error))
{
	$nombre = $_POST['nombre'];
	$resumen = $_POST['descripcion'];
	$tipo = $_POST['type'];	
	$fields = "nombre, descripcion, categoria, documento";
	$fecha = strftime("%A %d de %B del %Y");
			
	if (!empty($_POST['id_edit'])) {
		$id = $_POST['id_edit'];
		$error = $ctrl->editData("biblioteca", $id, $fields, $nombre, $resumen, $tipo, $filename);
	}
	else
		$error = $ctrl->addData("biblioteca", $nombre, $resumen, $tipo, $filename, $fecha);
}

echo $error;

die;
?>
