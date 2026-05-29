<?php
declare(strict_types=1);
include_once('clases/cargador.php');
$sesion = new sesion();

if (isset($_POST['usuario']) && ($_POST['usuario']!='')) {
	$sesion->conectarse($_POST['usuario'],$_POST['contrasena']);
    	if ($sesion->activo=='si') {
	echo 1;
	}
	else if ($sesion->activo=='no'){
	echo 2;
	}
	else {
	echo 3;
	}

}
else echo "No se indico un usuario";
?>


