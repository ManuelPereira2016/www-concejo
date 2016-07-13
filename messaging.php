<?php
require_once("controller/controller.php");
$ctrl = new controller();

$amount = 4; // Cantidad de elementos a mostrar.
$pagActual = 0;
$pagAnterior = 0;
$pagSiguiente = 0;

if(empty($_POST['pag']))
	$pagActual = 0;
else
	$pagActual=$_POST['pag'];

$pagAnterior = $pagActual-1;
$pagSiguiente = $pagActual+1;

$lista =$ctrl->getContent("contacto", (($pagActual-1)*$amount), $amount);
$filas = $ctrl->getAmount("contacto");
$paginas = ceil($filas/$amount);

$var = '<table class="table table-bordered table-condensed table-hover">
		<tr><th style="background-color:#cccccc;padding:5px;">Nombre:</th>
		<th style="background-color:#cccccc;padding:5px;">Correo:</th>
		<th style="background-color:#cccccc;padding:5px;width:10px"></th>
		<th style="background-color:#cccccc;padding:5px;width:10px"></th>
		</tr>';

foreach ($lista as $values) {
	$var .= '<tr><td style="padding=5px;">'.$values['nombre'].'</td>
	<td style="padding=5px;">'.$values['email'].'</td>
	<td style="padding=5px;"><span class="link" onclick="messaging(&#39;DEL&#39;,'.
	$values['id'].')">Borrar</span></td><td style="padding=5px;">
	<span class="link" onclick="messaging(&#39;READ&#39;,'.$values['id'].')">Abrir</span></td></tr>';
}

$var .= '</table>';
$var .= '<ul class="pager">';
if ($pagSiguiente <= $paginas)
	$var .= '<li class="next"><span class="link" onclick="messaging(&#39;NEXT&#39;, 0)"> Siguientes <small>>></small></span></li>';
if ($pagAnterior > 0)
	$var .= '<li class="previous"><span class="link" onclick="messaging(&#39;PREV&#39;, 0)"><small><<</small> Anteriores</span>
		</li>';

$var .= '</ul>';

if (!empty($_POST['id']) || !empty($_POST['op'])) {
	$id = $_POST['id'];

	$operation = $_POST['op'];

	if ($operation == 'DEL') {
		$ctrl->delData("contacto", $id);
		$var .= '<div id="confirmation">El mensaje se elimino con exito.</div>';
	}
	if ($operation == 'READ') {
		$fila = $ctrl->getData("contacto", null, $id);

		$var .= '<div id="mail" style="clear:both;" class="well well-sm">';

		$var .= '<span class="label label-primary spanMail">De:</span><STRONG> '.
		$fila['nombre'].'</STRONG><br/><br/><span class="label label-primary spanMail">Mail:</span> '.
		$fila['email'].'<br/><br/><span class="label label-primary spanMail">Mensaje:</span> '.
		$fila['mensaje'].'</div>';
	}
}

echo $var;

?>