<?php 
session_start();

require("controller/controller.php"); // funciones para tratar la BD
$ctrl = new controller();

if ( !empty($_POST['salir']) )
{
	session_unset();
	session_destroy();
	die;
}

// Revisamos si la sesion ya esta iniciada.
if ( !empty($_SESSION['usuario']) && !empty($_SESSION['pass']) )
{
	header('location: index.php');
	die;
}

if (!empty($_POST['loginbt'])) {
	$user = $ctrl->cleanInput($_POST['usuario']);
	$pass = $ctrl->cleanInput($_POST['pass']);

	$error = $ctrl->verifyAdmin($user, $pass);

	if (!empty($error)) {
		header("location: login&error=$error&");
	}		
}
?>
