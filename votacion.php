<?php
require_once("views/vista.php");
require_once("controller/controller.php");
$ctrl = new controller();

if (!empty($_GET['opcion'])) {
    $opcion = array("opcion", $_GET['opcion']);

	$fila = $ctrl->getDataWhere("encuestaOpciones", $opcion);
	$newVal = $fila['valor']+1;
	$fields = "valor";

	$ctrl->editData("encuestaOpciones", $fila['id'], $fields, $newVal);

}

$view = new vista();

$view->votacion();

?>