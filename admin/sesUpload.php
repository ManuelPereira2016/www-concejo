<?php
require_once("../controller/controller.php");
$ctrl = new controller();

if (!empty($_POST['dele'])) {
	$ctrl->delData("calendario", $_POST['dele']);
	die;
}

$sesion = $_POST['sesion'];
$day = $_POST['day'];
$month = $_POST['month'];
$year = $_POST['year'];
	
$error = $ctrl->addData("calendario", $day, $month, $year, $sesion);

echo $error;

die;
?>