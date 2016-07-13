<?php
require_once("views/vista.php");

error_reporting(E_ALL);

if (empty($_SESSION))
	session_start();

$view = new vista();
$view->crearHeader();

if (!empty($_GET['mostrar'])) $view->mostrar();
elseif (isset($_GET['biografias']) || isset($_GET['biografias_id'])) $view->biografias();
elseif (isset($_GET['directiva'])) $view->directiva();
elseif (isset($_GET['biblioteca'])) $view->biblioteca();
elseif (isset($_GET['fotos'])) $view->fotos();
elseif (isset($_GET['vid'])) $view->vid();
elseif (isset($_GET['mapa'])) $view->map();
elseif (isset($_GET['vision'])) $view->vision();
elseif (isset($_GET['himno'])) $view->himno();
elseif (isset($_GET['comisiones'])) $view->comision();
elseif (isset($_GET['calendario'])) $view->calendario();
elseif (isset($_GET['historia'])) $view->historia();
elseif (isset($_GET['login'])) $view->login();
elseif (!empty($_POST['contacto'])) {
	$name = $_POST['nombre'];
	$mail = $_POST['correo'];
	$message = $_POST['mensaje'];

	$view->contacto($name, $mail, $message);
}
// Pagina principal
//elseif (empty($_GET) && empty($_POST['contacto']) || !empty($_GET['pag'])) $view->index();
else
	$view->index();

$view->crearPanel();
$view->crearFooter();
?>
