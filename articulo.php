<?php
declare(strict_types=1);
require_once('clases/cargador.php');
$sesion = new sesion();
$conectarBD = new conexion();
$conn = $conectarBD->getConexion();

if (!$sesion->conectado) {
	header("location: index.php");
}

//CONSULTA DE FAMILIAS PARA INSERTAR ARTICULOS
$stmt = $conn->prepare("SELECT * FROM familias WHERE CodUsu = ?");
$stmt->bind_param('s', $sesion->CodUsu);
$stmt->execute();
$resultado_familias = $stmt->get_result();

//CONSULTA DE PROVEEDORES PARA INSERTAR ARTICULOS
$stmt2 = $conn->prepare("SELECT * FROM proveedores WHERE CodUsu = ?");
$stmt2->bind_param('s', $sesion->CodUsu);
$stmt2->execute();
$resultado_proveedores = $stmt2->get_result();

//CONSULTA ARTICULOS PARA CALCULAR EL CODIGO E INSERTAR
$stmt3 = $conn->prepare("SELECT CodArt FROM articulos WHERE CodUsu = ? ORDER BY CodArt DESC");
$stmt3->bind_param('s', $sesion->CodUsu);
$stmt3->execute();
$consulta_articulo = $stmt3->get_result();
$filas_consulta_articulo = $consulta_articulo->fetch_assoc();
$stmt3->close();

$rango = $sesion->CodUsu * 100;
if ($filas_consulta_articulo === null || !$filas_consulta_articulo['CodArt']) {
	$CA = $rango + 1;
}
else if (($filas_consulta_articulo['CodArt']) < ($rango + 100)) {
	$CA = $filas_consulta_articulo['CodArt'] + 1;
}
else {
	echo "Se ha alcanzado el numero maximo de articulos para este usuario";
	$CA = 0;
}

//INSERTAR ARTICULOS
if (isset($_POST["ICodFam"]) && (isset($_POST["INomArt"])) && ($_POST["INomArt"]!='')) {
	$ICodFam = $conn->real_escape_string($_POST['ICodFam']);
	$INomArt = $conn->real_escape_string($_POST['INomArt']);
	$IDesArt = $conn->real_escape_string($_POST['IDesArt']);
	$ICodPro = $conn->real_escape_string($_POST['ICodPro']);
	$Iprecio = $conn->real_escape_string($_POST['Iprecio']);

	$stmt4 = $conn->prepare("INSERT INTO articulos (CodArt,CodFam,NomArt,DesArt,CodPro,precio,PreCom, cantidad,CodUsu) VALUES (?, ?, ?, ?, ?, ?, 0, 0, ?)");
	$stmt4->bind_param('sssssss', $CA, $ICodFam, $INomArt, $IDesArt, $ICodPro, $Iprecio, $sesion->CodUsu);
	$stmt4->execute();
	$stmt4->close();
	header("Location: articulo.php");
}

//CONSULTA ARTICULOS PARA ACTUALIZARLOS
$stmt5 = $conn->prepare("SELECT * FROM articulos WHERE CodUsu = ?");
$stmt5->bind_param('s', $sesion->CodUsu);
$stmt5->execute();
$resultado_articulos = $stmt5->get_result();

//ACTUALIZAR
if (isset($_POST["CodArt"])) {
	$CodFam = $conn->real_escape_string($_POST['CodFam']);
	$NomArt = $conn->real_escape_string($_POST['NomArt']);
	$DesArt = $conn->real_escape_string($_POST['DesArt']);
	$CodPro = $conn->real_escape_string($_POST['CodPro']);
	$precio = $conn->real_escape_string($_POST['precio']);
	$PreCom = $conn->real_escape_string($_POST['PreCom']);
	$cantidad = $conn->real_escape_string($_POST['cantidad']);
	$CodArt = $conn->real_escape_string($_POST['CodArt']);

	$stmt6 = $conn->prepare("UPDATE articulos SET CodFam = ?, NomArt = ?, DesArt = ?, CodPro = ?, precio = ?, PreCom = ?, cantidad = ? WHERE CodArt = ? AND CodUsu = ?");
	$stmt6->bind_param('sssssssss', $CodFam, $NomArt, $DesArt, $CodPro, $precio, $PreCom, $cantidad, $CodArt, $sesion->CodUsu);
	$stmt6->execute();
	$stmt6->close();
	header("Location: articulo.php");
}

//CONSULTA ARTICULOS PARA ELIMINARLOS
$stmt7 = $conn->prepare("SELECT * FROM articulos WHERE (cantidad=0 or cantidad is NULL) AND CodUsu = ?");
$stmt7->bind_param('s', $sesion->CodUsu);
$stmt7->execute();
$resultado_articulos2 = $stmt7->get_result();

//ELIMINAR ARTICULO
if (isset($_POST["BCodArt"])) {
	$BCodArt = $conn->real_escape_string($_POST["BCodArt"]);

	$stmt8 = $conn->prepare("DELETE FROM articulos WHERE CodArt = ? AND CodUsu = ?");
	$stmt8->bind_param('ss', $BCodArt, $sesion->CodUsu);
	$stmt8->execute();
	$stmt8->close();
	header("Location: articulo.php");
}

///////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////
//CONSULTA FAMILIAS PARA CALCULAR EL CODIGO E INSERTAR
$stmt9 = $conn->prepare("SELECT CodFam FROM familias WHERE CodUsu = ? ORDER BY CodFam DESC");
$stmt9->bind_param('s', $sesion->CodUsu);
$stmt9->execute();
$consulta_familia = $stmt9->get_result();
$filas_consulta_familia = $consulta_familia->fetch_assoc();
$stmt9->close();

$rangoF = $sesion->CodUsu * 100;
if ($filas_consulta_familia === null || !$filas_consulta_familia['CodFam']) {
	$CF = $rangoF + 1;
}
else if (($filas_consulta_familia['CodFam']) < ($rangoF + 100)) {
	$CF = $filas_consulta_familia['CodFam'] + 1;
}
else {
	echo "Se ha alcanzado el numero maximo de familias para este usuario";
	$CF = 0;
}

//INSERTAR FAMILIA
if (isset($_POST["Inombre"]) && ($_POST["Inombre"]!='')) {
	$Inombre = $conn->real_escape_string($_POST["Inombre"]);

	$stmt10 = $conn->prepare("INSERT INTO familias (CodFam,nombre,CodUsu) VALUES (?, ?, ?)");
	$stmt10->bind_param('sss', $CF, $Inombre, $sesion->CodUsu);
	$stmt10->execute();
	$stmt10->close();
	header("Location: articulo.php");
}

//CONSULTA FAMILIA PARA ACTUALIZARLAS
$stmt11 = $conn->prepare("SELECT * FROM familias WHERE CodUsu = ?");
$stmt11->bind_param('s', $sesion->CodUsu);
$stmt11->execute();
$resultado_familias3 = $stmt11->get_result();

//ACTUALIZAR FAMILIAS
if (isset($_POST["FCodFam"])) {
	$Fnombre = $conn->real_escape_string($_POST['Fnombre']);
	$FCodFam = $conn->real_escape_string($_POST['FCodFam']);

	$stmt12 = $conn->prepare("UPDATE familias SET nombre = ? WHERE CodFam = ? AND CodUsu = ?");
	$stmt12->bind_param('sss', $Fnombre, $FCodFam, $sesion->CodUsu);
	$stmt12->execute();
	$stmt12->close();
	header("Location: articulo.php");
}

//CONSULTA FAMILIA PARA ELIMINARLAS
$stmt13 = $conn->prepare("SELECT familias.* FROM familias LEFT JOIN articulos on familias.CodFam=articulos.CodFam WHERE (CodArt IS NULL) AND familias.CodUsu = ?");
$stmt13->bind_param('s', $sesion->CodUsu);
$stmt13->execute();
$resultado_familias2 = $stmt13->get_result();

//ELIMINAR FAMILIA
if (isset($_POST["BCodFam"])) {
	$BCodFam = $conn->real_escape_string($_POST["BCodFam"]);

	$stmt14 = $conn->prepare("DELETE FROM familias WHERE CodFam = ? AND CodUsu = ?");
	$stmt14->bind_param('ss', $BCodFam, $sesion->CodUsu);
	$stmt14->execute();
	$stmt14->close();
	header("Location: articulo.php");
}
?>

<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Kamalakshi ::: Usado por <?php echo $_SESSION['nombreusuario'];?></title>
<link rel="stylesheet" type="text/css" href="estilo.css" />
<script type="text/javascript" src="js/js.js"></script>
<script type="text/javascript" src="js/articulo.js"></script>

<script type="text/javascript">
	function confirmar_borrar_articulo() {
	document.getElementById("confirmar_borrar_articulo").className='visible';	
	alert('¡ATENCION!\n Si elimina un articulo no se podra recuperar despues');
}
	function desconfirmar_borrar_articulo() {
	document.getElementById("confirmar_borrar_articulo").className='invisible';	
}
	function confirmar_borrar_familia() {
	document.getElementById("confirmar_borrar_familia").className='visible';
	alert('¡ATENCION!\n Si elimina una familia no se podra recuperar despues');	
}
	function desconfirmar_borrar_familia() {
	document.getElementById("confirmar_borrar_familia").className='invisible';	
}

</script>

</head>
<body style="background-color: black;">
<div class="iframes">
<!-- FAMILIAS-->
		<p><b>Familias</b></p>
	<!-- INICIO AGREGAR FAMILIA -->
	<a href="#" onClick="mostrar_nuevo_familia()" id="boton_nuevo_familia">Nueva familia <img src="img/nuevo.png" width="20px" height="20px" style="vertical-align: middle;"/></a>
	<div class="invisible" id="nuevo_familia">
		<div class="agregados">
		<span style="float: right;"><input  class="botonesrojos" type="button" value="X" onClick="ocultar_nuevo_familia()" /></span>
		<b>Nueva Familia: </b>
		<form name="agregar_familia" id="agregar_familia" method="post" action="articulo.php">
		<p>
		Nombre <input type="text" name="Inombre" id="Inombre" value="" size="" />
		</p>
		<p style="text-align: center;">
		<input type="button" value="Agregar" class="botones" onClick="comprobar_campos_familia()"/>
		</p>
		</form>
		</div>
	</div>
		<!-- FIN AGREGAR FAMILIA -->

		<!-- INICIO MODIFICAR FAMILIA -->
	<br /><br />
	<a href="#" onClick="mostrar_actualizar_familia()" id="boton_actualizar_familia">Actualizar familia <img src="img/actualizar.png" width="20px" height="20px" style="vertical-align: middle;"/></a>
	  <div class="invisible" id="actualizar_familia">
	    <div class="agregados">
		<span style="float: right;"><input  class="botonesrojos" type="button" value="X" onClick="ocultar_actualizar_familia()" /></span>
		<b>Actualizar Familia: </b>
		<table>
		   <tr>
			<td>Codigo</td>
			<td>Nombre</td>
			<td></td>
		   </tr>
		<?php while($filas_resultado_familias3 = $resultado_familias3->fetch_assoc()) { ?>
		   <tr>
			<form method="post" action="articulo.php" name="formactualizar_familia">
			<td>
			<input type="text" value="<?php echo $filas_resultado_familias3['CodFam']?>" name="FCodFam" size="6" readonly="readonly"/>
			</td>

			
			<td>	
			<input type="text" value="<?php echo $filas_resultado_familias3['nombre']?>" name="Fnombre"/>
			</td>
			
			<td>
			<input type="submit" value="Actualizar" class="botones"/>
			</td>	
			</form>			
		   </tr>
		  
		<?php } ?>
		</table>
	     
          </div>
	</div>
	<!-- FIN MODIFICAR FAMILIA -->

	<!-- INICIO BORRAR FAMILIA -->
	
	<br /><br />
	<a href="#" onClick="mostrar_eliminar_familia()" id="boton_eliminar_familia">Eliminar familia <img src="img/borrar.png" width="20px" height="20px" style="vertical-align: middle;"/></a>
	  <div class="invisible" id="eliminar_familia">
	    <div class="agregados">
		<span style="float: right;"><input  class="botonesrojos" type="button" value="X" onClick="ocultar_eliminar_familia()" /></span>
		<b>Eliminar Familia: (solo las que no tienen articulos relacionados)</b>
		<form action="articulo.php" method="post" name="borrar_familia">		
		<select name="BCodFam" onChange="desconfirmar_borrar_familia()">
		<?php while($filas_resultado_familias2 = $resultado_familias2->fetch_assoc()) { ?>
			<option value="<?php echo $filas_resultado_familias2['CodFam']?>"><?php echo $filas_resultado_familias2['CodFam']?> (<?php echo $filas_resultado_familias2['nombre']?>)</option>	
		<?php } ?>
		</select>
		<a href="#" onClick="confirmar_borrar_familia()">Eliminar <img src="img/borrar.png" width="20px" height="20px" style="vertical-align: middle;"/></a>
		<p class="invisible" id="confirmar_borrar_familia">
		&iquest;Realmente desea elimiar la familia? Esta accion es irreversible.
		<input type="button" onClick="desconfirmar_borrar_familia()" value="NO, volver" class="botones"/> <input type="submit" value="SI, Eliminar" class="botonesrojos"/>
		<p>
		</form>
	  </div>
	</div>
	<!-- FIN BORRAR FAMILIA -->
<!-- FIN FAMILIA -->	
<!-- FIN FAMILIAS -->
<hr />
<!-- Articulos -->		
	<p><b>Articulos</b></p>
	<!-- INICIO AGREGAR ARTICULO -->
	<a href="#" onClick="mostrar_nuevo_articulo()" id="boton_nuevo_articulo">Nuevo articulo <img src="img/nuevo.png" width="20px" height="20px" style="vertical-align: middle;"/></a>
	<div class="invisible" id="nuevo_articulo">
		<div class="agregados">
		<span style="float: right;"><input  class="botonesrojos" type="button" value="X" onClick="ocultar_nuevo_articulo()" /></span>
		<b>Nuevo Articulo: </b>
		<form name="agregar_articulo" id="agregar_articulo" method="post" action="articulo.php">
		<p>
		<input type="hidden" value="<?php echo date('Y-n-j');?>" name="Ifecha"/>
		Familia <select name="ICodFam">
				<?php while($filas_resultado_familias = $resultado_familias->fetch_assoc()) { ?>
				<option value="<?php echo $filas_resultado_familias['CodFam']?>"> <?php echo $filas_resultado_familias['CodFam']?> (<?php echo $filas_resultado_familias['nombre']?>)
				</option>
				<?php } ?>			
			</select>
		Nombre <input type="text" name="INomArt" id="INomArt" value="" size="" />
		Descripcion <input type="text" name="IDesArt" value="" size="" />
		</p>

		<p>		
		Proveedor <select name="ICodPro">
			 	<?php while($filas_resultado_proveedores = $resultado_proveedores->fetch_assoc()) { ?>
				<option value="<?php echo $filas_resultado_proveedores['CodPro']?>"> <?php echo $filas_resultado_proveedores['CodPro']?> (<?php echo $filas_resultado_proveedores['NomPro']?>)
				</option>
				<?php } ?>
			</select>
		Precio estimado de venta al publico<input type="text" name="Iprecio" id="Iprecio" value="" size="5" maxlength="6" />
		</p>
		<p style="text-align: center;">
		<input type="button" value="Agregar" class="botones" onClick="comprobar_campos_articulo()"/>
		</p>
		</form>
		</div>
	</div>
		<!-- FIN AGREGAR ARTICULO -->

		<!-- INICIO MODIFICAR ARTICULO -->
	<br />	<br />
		<a href="#" onClick="mostrar_actualizar_articulo()" id="boton_actualizar_articulo">Actualizar articulo <img src="img/actualizar.png" width="20px" height="20px" style="vertical-align: middle;"/></a>

	  <div class="invisible" id="actualizar_articulo">
	    <div class="agregados">
		<span style="float: right;"><input  class="botonesrojos" type="button" value="X" onClick="ocultar_actualizar_articulo()" /></span>
		<b>Actualizar Articulo: </b>
		<table>
		   <tr>
			<td>Codigo</td>
			<td>Familia</td>
			<td>Proveedor</td>
			<td>Nombre</td>
			<td>Descripcion</td>
			<td>Precio</td>
			<td>P/compra</td>
			<td>Stock</td>
			<td></td>	
		   </tr>
		<?php while($filas_resultado_articulos = $resultado_articulos->fetch_assoc()) { ?>
		   <tr>
			<form method="post" action="articulo.php" name="formactualizar">
			<td>
			<input type="text" value="<?php echo $filas_resultado_articulos['CodArt']?>" name="CodArt" size="6" readonly="readonly"/>
			</td>

			<td>			
			<input type="text" name="CodFam" value="<?php echo $filas_resultado_articulos['CodFam']?>" size="7" readonly="readonly"/>
			</td>			
			
			
			<td>
			<input type="text" name="CodPro" value="<?php echo $filas_resultado_articulos['CodPro']?>" size="7" readonly="readonly"/>
			</td>
			
			<td>	
			<input type="text" value="<?php echo $filas_resultado_articulos['NomArt']?>" name="NomArt" size="8"/>
			</td>			
			
			<td>					
			<input type="text" value="<?php echo $filas_resultado_articulos['DesArt']?>" name="DesArt" size="7"/>
			</td>
			<td>
			<input type="text" value="<?php echo $filas_resultado_articulos['precio']?>" name="precio" size="4" maxlength="6" /><?php echo $sesion->moneda;?>
			</td>
			<td>		
			<input type="text" value="<?php echo $filas_resultado_articulos['PreCom']?>" name="PreCom" size="4" maxlength="6" /><?php echo $sesion->moneda;?>
			
			</td>
			<td>
			<input type="text" value="<?php echo $filas_resultado_articulos['cantidad']?>" name="cantidad" size="4" maxlength="6" readonly="readonly"/>
			<td>
			<input type="submit" value="Actualizar" class="botones"/>
			</td>
			</td>
						
			

			 </form>
		   </tr>
		  
		<?php } ?>
		</table>
	     
          </div>
	</div>
	<!-- FIN MODIFICAR ARTICULO -->

	<!-- INICIO BORRAR ARTICULO -->
	
	<br /><br />
	<a href="#" onClick="mostrar_eliminar_articulo()" id="boton_eliminar_articulo">Eliminar articulo <img src="img/borrar.png" width="20px" height="20px" style="vertical-align: middle;"/></a>
	  <div class="invisible" id="eliminar_articulo">
	    <div class="agregados">
		<span style="float: right;"><input  class="botonesrojos" type="button" value="X" onClick="ocultar_eliminar_articulo()" /></span>
		<b>Eliminar Articulo: (solo si no hay en stock)</b>
		<form action="articulo.php" method="post" name="borrar_articulo">		
		<select name="BCodArt" onChange="desconfirmar_borrar_articulo()">
		<?php while($filas_resultado_articulos2 = $resultado_articulos2->fetch_assoc()) { ?>
			<option value="<?php echo $filas_resultado_articulos2['CodArt']?>"><?php echo $filas_resultado_articulos2['CodArt']?> (<?php echo $filas_resultado_articulos2['NomArt']?>)</option>	
		<?php } ?>
		</select>
		<a href="#" onClick="confirmar_borrar_articulo()" id="boton_eliminar_articulo">Eliminar <img src="img/borrar.png" width="20px" height="20px" style="vertical-align: middle;"/></a>
		<p class="invisible" id="confirmar_borrar_articulo">
		&iquest;Realmente desea elimiar el articulo? Esta accion es irreversible.
		<input type="button" onClick="desconfirmar_borrar_articulo()" value="NO, volver" class="botones"/> <input type="submit" value="SI, Eliminar" class="botonesrojos"/>
		<p>
		</form>
	  </div>
	</div>
	<!-- FIN BORRAR ARTICULO -->
<!-- FIN ARTICULO -->	
</div>
</body>
</html>
