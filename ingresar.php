<?php 
include_once("clases/cargador.php");
$sesion=new sesion();
if (isset($_POST['accion'])) include_once($_POST['accion'].".php");
else { 
	if ($sesion->conectado==true) include_once('adentro.php');
	else {
	echo "no estas conectado";
 	} 
}
?>
