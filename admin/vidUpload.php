<?php
require_once("../controller/controller.php");
$ctrl = new controller();

if (!empty($_POST['dele'])) {
	$ctrl->delData("galeria_videos", $_POST['dele']);
	die;
}

if (!empty($_FILES['video']['name']))
	$error = $ctrl->uploadVideo();

if (empty($error))
{
	$nombre = $_POST['nombre'];
	$resumen = $_POST['descripcion'];
	$filename = $_FILES['video']['name'];
	$fecha = strftime("%A %d de %B del %Y");
	$error = $ctrl->addData("galeria_videos", $nombre, $resumen, $filename, $fecha);
}

echo $error;
	
die;
?>
