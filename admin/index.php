<?php
require("../views/vista.php");

if (empty($_SESSION))
	session_start();

$view = new vista();
$view->crearHeader();

if (empty($_GET)) $view->admHome();

if (isset($_GET['articulos'])) $view->admArticulos();

if (isset($_GET['biografias'])) $view->admBiografias();

if (isset($_GET['junta'])) $view->admJunta();

if (isset($_GET['docs'])) $view->admDocs();

if (isset($_GET['fotos'])) $view->admFotos();

if (isset($_GET['video'])) $view->admVideos();

if (isset($_GET['sesion'])) $view->admSesiones();

if (isset($_GET['encuesta'])) $view->admEncuestas();

if (isset($_GET['mensajes'])) $view->admContacto();

$view->crearPanel();
$view->crearFooter();

?>
