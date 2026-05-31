<?php
declare(strict_types=1);
include_once('clases/cargador.php');
$sesion = new sesion();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="ISO-8859-1"/>
<meta name="description" content="Completo terminal punto de venta desde la nube. Gestione su comercio sin gastar dinero en un TPV " />
<meta name="keywords"content="tpv,TPV,terminal punto de venta,venta,nubenta,nuventa,tpv online,tpv desde la nube,nube,terminal,punto,venta" />
<link rel="stylesheet" href="index.css" type="text/css" />
<title>Nubenta-TPV desde la nube</title>
<link rel="shortcut icon" href="img/favicon.ico"/>
<meta name="description" content="">
<meta name="keywords" content="">
<script type="text/javascript" src="js/ajax.js"></script>
<script type="text/javascript" src="js/ingresar.js"></script>
<script type="text/javascript" src="js/registrarse.js"></script>
<meta name="google-site-verification" content="447rD6Gy5oNg2lfeHTB7xff5uOE7nGd1CLPx4mNEhac" />
<body>
	<!--Inicio Div para contenidos ajax -->
	<div class="ajax" id="ajax">
	</div>
	<!-- Fin div para contenidos ajax-->
	<!-- Inicio cabecera -->	
	<div class="cabecera">
		<span class="titulo">
		<img src="img/logo.png" width="200" height="80" alt="nubenta"/> Beta
		</span>
		<span class="ingreso">
		<a href="#" onclick="mostrarIngreso();return false;">Ingresar</a> <a href="#" onclick="mostrarRegistro();return false;">Registrarse</a>
		</span>
	</div>
	<!-- Fin cabecera -->	
	<div style="clear:both"></div>
	<!-- Inicio contenedor -->
	<div class="concuchara">
		<div class="medio">
			<div class="textomedio">
				<p class="encabezado">				
				Nubenta le ofrece un completo servicio de terminal de punto de venta<br />
				Completamente Online, desde la nube.<br /> 
				</p>
				<img src="img/cajaNubenta.png" width="200" height="213" alt="nubenta" style="float:right;"/>
				<ul>
					<li>Creacion de Proveedores, Bancos y cuentas</li>
					<li>Mantenimiento de Stock</li>
					<li>Agrupacion de productos por familias</li>
					<li>Impresion de tickets y tickets de regalo</li>
					<li>Consulta y busqueda detallada de todos los elementos</li>
					<li>Caja diaria y control de entradas-salidas</li>
					<li>...Y muchas cosas mas</li>						
				</ul>
				<br />
				<div style="margin:0 auto; width:300px">
					<a href="#" class="botonGrande" onclick="mostrarTutorial();return false;">&iquest;Como empezar?</a>
				</div>
			</div>
		</div>
	</div>
	<!-- Fin contenedor -->
	<div style="clear:both"></div>
	<br /><br />
	<!-- INICIO FINAL -->
	<div class="final">
		<div style="float:right;">		
			<a href="" onclick="mostrarContacto();return false;">Contacto</a>
			<a href="" onclick="mostrarCondiciones();return false;">Condiciones de uso</a>
		</div>
		<div style="clear:both"></div>
	<hr />
		Dise&ntilde;o y desarrollo del sitio: <a href="http://sebastianporteiro.com">Sebastian Porteiro </a> <img src="https://www.sebastianporteiro.com/img/favicon.ico" alt="sebastianporteiro.com"/><br />
	</div>
	<!-- FIN FINAL -->
</body>
</html>
