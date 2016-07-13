<?php
require_once("../controller/controller.php");
$ctrl = new controller();

if (!empty($_POST['dele'])) {
	$ctrl->delData("galeria_imagenes", $_POST['dele']);
	die;
}

if (!empty($_FILES['foto']['name']))
	$error = $ctrl->uploadfoto();
	
if (empty($error))
{
	$nombre = $_POST['nombre'];
	$resumen = $_POST['descripcion'];
	$filename = $_FILES['foto']['name'];
	$fecha = strftime("%A %d de %B del %Y");
	$error = $ctrl->addData("galeria_imagenes", $nombre, $resumen, $filename, $fecha);
}

echo $error;
	
die;
?>
