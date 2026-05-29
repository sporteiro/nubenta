<?php
	require_once("clases/cargador.php");
	$sesion=new sesion();
	$sesion->desconectarse();
	header('Location: index.php');
?>
