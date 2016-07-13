<?php
require_once("controller/controller.php");
$ctrl = new controller();

$month = strtoupper($_POST['mes']);

$lista = $ctrl->getData("calendario", null,
	null, $month);

$var = '<table class="table table-bordered table-condensed table-hover">
		<tr><th style="background-color:#cccccc;padding:5px;">'.$month.'</th></tr>';

foreach ($lista as $values) {
	$var .= "<tr><td><span>".$values['dia']." de ".$values['mes']." del ".$values['año'].". ".$values['sesion']."</span></td></tr>" ;
	
}

$var .= "</table>";

echo $var;

?>