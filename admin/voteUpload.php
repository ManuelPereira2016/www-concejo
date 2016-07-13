<?php
require_once("../controller/controller.php");
$ctrl = new controller();

if (!empty($_POST['dele'])) {
	$ctrl->delData("encuestaOpciones", $_POST['dele']);
	die;
}

if ($_POST['opt'] == 1) {
	$opcion = $_POST['opcion'];

	// By default adds a relationship from this table to id of the first encuesta.(1)
	$error = $ctrl->addData("encuestaOpciones", 1, $opcion, 0);

} else {
	$encuesta = $_POST['encuesta'];
	$fecha = strftime("%A, %d de %B del %Y");
	$fields = "titulo, fecha";

	// By default we modify or update only first encuesta on table.
	$ctrl->editData("encuesta", 1, $fields, $encuesta, $fecha);
}

echo $error;

die;
?>