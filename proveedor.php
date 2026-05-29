<?php
declare(strict_types=1);
require_once('clases/cargador.php');
$sesion = new sesion();
$conectarBD = new conexion();
$conn = $conectarBD->getConexion();

if (!$sesion->conectado) {
	header("location: index.php");
}

//CONSULTA PROVEEDORES PARA CALCULAR EL CODIGO E INSERTAR
$stmt = $conn->prepare("SELECT CodPro FROM proveedores WHERE CodUsu = ? ORDER BY CodPro DESC");
$stmt->bind_param('s', $sesion->CodUsu);
$stmt->execute();
$resultado_proIN = $stmt->get_result();
$filas_consulta_proIN = $resultado_proIN->fetch_assoc();
$stmt->close();

$rango = $sesion->CodUsu * 100;
if ($filas_consulta_proIN === null || !$filas_consulta_proIN['CodPro']) {
	$CP = $rango + 1;
}
else if (($filas_consulta_proIN['CodPro']) < ($rango + 100)) {
	$CP = $filas_consulta_proIN['CodPro'] + 1;
}
else {
	echo "Se ha alcanzado el numero maximo de proveedores para este usuario";
	$CP = 0;
}
//INSERTAR PROVEEDORES
if (isset($_POST["INomPro"]) && ($_POST["INomPro"]!='')) {
	$INomPro = $conn->real_escape_string($_POST['INomPro']);
	$INIF_CIF = $conn->real_escape_string($_POST['INIF/CIF']);
	$Idireccion = $conn->real_escape_string($_POST['Idireccion']);
	$Ipais = $conn->real_escape_string($_POST['Ipais']);
	$Ilocalidad = $conn->real_escape_string($_POST['Ilocalidad']);
	$Iprovincia = $conn->real_escape_string($_POST['Iprovincia']);
	$Itelefono = $conn->real_escape_string($_POST['Itelefono']);
	// Validar que el teléfono sea numérico y esté dentro del rango de INT
	$Itelefono = preg_replace('/[^0-9]/', '', $Itelefono);
	if ($Itelefono === '') {
		$Itelefono = '0';
	}
	$Itelefono = min(intval($Itelefono), 2147483647);
	$Iemail = $conn->real_escape_string($_POST['Iemail']);

	$stmt2 = $conn->prepare("INSERT INTO proveedores (CodPro,NomPro,NIF_CIF,direccion,pais,localidad,provincia,telefono,email,CodUsu) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
	$stmt2->bind_param('ssssssssss', $CP, $INomPro, $INIF_CIF, $Idireccion, $Ipais, $Ilocalidad, $Iprovincia, $Itelefono, $Iemail, $sesion->CodUsu);
	$stmt2->execute();
	$stmt2->close();
	header("Location: proveedor.php");
}



//CONSULTA PROVEEDORES PARA ACTUALIZARLOS
$stmt3 = $conn->prepare("SELECT * FROM proveedores WHERE CodUsu = ? ORDER BY CodPro");
$stmt3->bind_param('s', $sesion->CodUsu);
$stmt3->execute();
$resultado_proveedores = $stmt3->get_result();

//ACTUALIZAR
if (isset($_POST["CodPro"])) {
	$CodPro = $conn->real_escape_string($_POST['CodPro']);
	$NomPro = $conn->real_escape_string($_POST['NomPro']);
	$NIF_CIF = $conn->real_escape_string($_POST['NIF_CIF']);
	$direccion = $conn->real_escape_string($_POST['direccion']);
	$pais = $conn->real_escape_string($_POST['pais']);
	$localidad = $conn->real_escape_string($_POST['localidad']);
	$provincia = $conn->real_escape_string($_POST['provincia']);
	$telefono = $conn->real_escape_string($_POST['telefono']);
	$email = $conn->real_escape_string($_POST['email']);

	$stmt4 = $conn->prepare("UPDATE proveedores SET NomPro=?, NIF_CIF=?, direccion=?, pais=?, localidad=?, provincia=?, telefono=?, email=? WHERE CodPro=? AND CodUsu=?");
	$stmt4->bind_param('ssssssssss', $NomPro, $NIF_CIF, $direccion, $pais, $localidad, $provincia, $telefono, $email, $CodPro, $sesion->CodUsu);
	$stmt4->execute();
	$stmt4->close();
	header("Location: proveedor.php");
}






//CONSULTA PROVEEDORES PARA ELIMINARLOS
$stmt5 = $conn->prepare("SELECT proveedores.* FROM proveedores LEFT JOIN articulos on proveedores.CodPro=articulos.CodPro WHERE CodArt IS NULL AND proveedores.CodUsu = ?");
$stmt5->bind_param('s', $sesion->CodUsu);
$stmt5->execute();
$resultado_proveedores2 = $stmt5->get_result();

//ELIMINAR PROVEEDORES
if (isset($_POST["BCodPro"])) {
	$BCodPro = $conn->real_escape_string($_POST["BCodPro"]);
	$stmt6 = $conn->prepare("DELETE FROM proveedores WHERE CodPro = ? AND CodUsu = ?");
	$stmt6->bind_param('ss', $BCodPro, $sesion->CodUsu);
	$stmt6->execute();
	$stmt6->close();
	header("Location: proveedor.php");
}


///////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////

//CONSULTA BANCOS PARA CALCULAR EL CODIGO E INSERTAR
$stmt7 = $conn->prepare("SELECT CodBan FROM bancos WHERE CodUsu = ? ORDER BY CodBan DESC");
$stmt7->bind_param('s', $sesion->CodUsu);
$stmt7->execute();
$resultado_proBA = $stmt7->get_result();
$filas_consulta_proBA = $resultado_proBA->fetch_assoc();
$stmt7->close();

$rangoB = $sesion->CodUsu * 100;
if ($filas_consulta_proBA === null || !$filas_consulta_proBA['CodBan']) {
	$CB = $rangoB + 1;
}
else if (($filas_consulta_proBA['CodBan']) < ($rangoB + 100)) {
	$CB = $filas_consulta_proBA['CodBan'] + 1;
}
else {
	echo "Se ha alcanzado el numero maximo de bancos para este usuario";
	$CB = 0;
}

//INSERTAR BANCO

if (isset($_POST["Inombre"]) && ($_POST["Inombre"]!='')) {
	$Inombre = $conn->real_escape_string($_POST['Inombre']);

	$stmt8 = $conn->prepare("INSERT INTO bancos (CodBan,nombre,CodUsu) VALUES (?, ?, ?)");
	$stmt8->bind_param('sss', $CB, $Inombre, $sesion->CodUsu);
	$stmt8->execute();
	$stmt8->close();
	header("Location: proveedor.php");
}


//CONSULTA BANCO PARA ACTUALIZARLAS
$stmt9 = $conn->prepare("SELECT * FROM bancos WHERE CodUsu = ?");
$stmt9->bind_param('s', $sesion->CodUsu);
$stmt9->execute();
$resultado_bancos3 = $stmt9->get_result();



//ACTUALIZAR BANCOS
if (isset($_POST["CodBan"])) {
	$nombre = $conn->real_escape_string($_POST['nombre']);
	$CodBan = $conn->real_escape_string($_POST['CodBan']);

	$stmt10 = $conn->prepare("UPDATE bancos SET nombre = ? WHERE CodBan = ? AND CodUsu = ?");
	$stmt10->bind_param('sss', $nombre, $CodBan, $sesion->CodUsu);
	$stmt10->execute();
	$stmt10->close();
	header("Location: proveedor.php");
}






//CONSULTA BANCO PARA ELIMINARLAS
$stmt11 = $conn->prepare("SELECT bancos.* FROM bancos left join cuentas on bancos.CodBan=cuentas.CodBan WHERE CodPro IS NULL AND bancos.CodUsu = ?");
$stmt11->bind_param('s', $sesion->CodUsu);
$stmt11->execute();
$resultado_bancos2 = $stmt11->get_result();

//ELIMINAR BANCO
if (isset($_POST["BCodBan"])) {
	$BCodBan = $conn->real_escape_string($_POST["BCodBan"]);

	$stmt12 = $conn->prepare("DELETE FROM bancos WHERE CodBan = ? AND CodUsu = ?");
	$stmt12->bind_param('ss', $BCodBan, $sesion->CodUsu);
	$stmt12->execute();
	$stmt12->close();
	header("Location: proveedor.php");
}



/////////////////////////////////////////////////////////////////////////////////////////////////////////
//CUENTAS////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////


//Consulta proveedores para insertar cuentas
$stmt13 = $conn->prepare("SELECT * FROM proveedores WHERE CodUsu = ?");
$stmt13->bind_param('s', $sesion->CodUsu);
$stmt13->execute();
$resultado_proveedoresC = $stmt13->get_result();

//Consulta de bancos para insertar cuentas
$stmt14 = $conn->prepare("SELECT * FROM bancos WHERE CodUsu = ?");
$stmt14->bind_param('s', $sesion->CodUsu);
$stmt14->execute();
$resultado_bancosC = $stmt14->get_result();

//INSERTAR CUENTA
if (isset($_POST["INumCue"]) && ($_POST["INumCue"]!='')) {
	$ICodBan2 = $conn->real_escape_string($_POST['ICodBan2']);
	$ICodPro2 = $conn->real_escape_string($_POST['ICodPro2']);
	$INumCue = $conn->real_escape_string($_POST['INumCue']);

	$stmt15 = $conn->prepare("INSERT INTO cuentas VALUES (?, ?, ?, ?)");
	$stmt15->bind_param('ssss', $ICodBan2, $ICodPro2, $INumCue, $sesion->CodUsu);
	$stmt15->execute();
	$stmt15->close();
	header("Location: proveedor.php");
}
//CONSULTA CUENTAS PARA ELIMINARLAS
$stmt16 = $conn->prepare("SELECT C.*,B.nombre,P.NomPro FROM cuentas C join bancos B join proveedores P on B.CodBan=C.CodBan AND P.CodPro=C.CodPro WHERE C.CodUsu = ?");
$stmt16->bind_param('s', $sesion->CodUsu);
$stmt16->execute();
$resultado_cuentas2 = $stmt16->get_result();

//ELIMINAR CUENTA
if (isset($_POST["BNumCue"])) {
	$BNumCue = $conn->real_escape_string($_POST["BNumCue"]);

	$stmt17 = $conn->prepare("DELETE FROM cuentas WHERE NumCue = ? AND CodUsu = ?");
	$stmt17->bind_param('ss', $BNumCue, $sesion->CodUsu);
	$stmt17->execute();
	$stmt17->close();
	header("Location: proveedor.php");
}

//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////

//Consulta paises para insertar proveedores
$stmt18 = $conn->prepare("SELECT * FROM paises");
$stmt18->execute();
$resultado_paises = $stmt18->get_result();

//Consulta provincias para insertar proveedores
$stmt19 = $conn->prepare("SELECT * FROM provincias WHERE CodPai='AR'");
$stmt19->execute();
$resultado_provincias = $stmt19->get_result();
?>

<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Kamalakshi ::: Usado por <?php echo $_SESSION['nombreusuario'];?></title>
<link rel="stylesheet" type="text/css" href="estilo.css" />
<script type="text/javascript" src="js/js.js"></script>
<script type="text/javascript" src="js/proveedor.js"></script>

<script type="text/javascript">
	function confirmar_borrar_proveedor() {
	document.getElementById("confirmar_borrar_proveedor").className='visible';	
	alert('¡ATENCION!\n Si elimina un proveedor no se podra recuperar despues');
}
	function desconfirmar_borrar_proveedor() {
	document.getElementById("confirmar_borrar_proveedor").className='invisible';	
}
	function confirmar_borrar_banco() {
	document.getElementById("confirmar_borrar_banco").className='visible';	
	alert('¡ATENCION!\n Si elimina un banco no se podra recuperar despues');
}
	function desconfirmar_borrar_banco() {
	document.getElementById("confirmar_borrar_banco").className='invisible';	
}
	function confirmar_borrar_cuenta() {
	document.getElementById("confirmar_borrar_cuenta").className='visible';	
	alert('¡ATENCION!\n Si elimina una cuenta no se podra recuperar despues');
}
	function desconfirmar_borrar_cuenta() {
	document.getElementById("confirmar_borrar_cuenta").className='invisible';	
}
</script>

</head>
<body style="background-color: black;">
<div class="iframes">
<!-- Proveedores -->		
	<p><b>Proveedores</b></p>
	<!-- INICIO AGREGAR PROVEEDORES -->
	<a href="#" onClick="mostrar_nuevo_proveedor()" id="boton_nuevo_proveedor">Nuevo proveedor <img src="img/nuevo.png" width="20px" height="20px" style="vertical-align: middle;"/></a>
	<div class="invisible" id="nuevo_proveedor">
		<div class="agregados">
		<span style="float: right;"><input  class="botonesrojos" type="button" value="X" onClick="ocultar_nuevo_proveedor()" /></span>
		<b>Nuevo Proveedor: </b>
		<form name="agregar_proveedor" method="post" action="proveedor.php" id="agregar_proveedor">
		<p>	
		Nombre <input type="text" name="INomPro"  id="INomPro" value="" size="" />
		NIF/CIF <input type="text" name="INIF/CIF" id="INIF/CIF" value="" size="" />
		</p>

		<p>		
		Direccion <input type="text" name="Idireccion" value="" size="" />
		Localidad <input type="text" name="Ilocalidad" value="" size="" />
		</p>
		<p>	
		Pais <select name="Ipais">
			<option value="AR" selected="selected">Argentina (AR)</option>
		<?php while($filas_resultado_paises = $resultado_paises->fetch_assoc()) { ?>

			<option value="<?php echo $filas_resultado_paises['CodPai']?>"> <?php echo $filas_resultado_paises['NomPai']?> (<?php echo $filas_resultado_paises['CodPai']?>) 
			</option>	
		<?php } ?>
		</select>

		Provincia <select name="Iprovincia">
				<option value="Buenos Aires" selected="selected">Buenos Aires</option>
				<option value="--Ninguna--">--Ninguna--</option>
		<?php while($filas_resultado_provincias = $resultado_provincias->fetch_assoc()) { ?>
			<option value="<?php echo $filas_resultado_provincias['NomProvincia']?>"> <?php echo $filas_resultado_provincias['NomProvincia']?> 
			</option>	
		<?php } ?>
		</select>
		</p>
				<p>		
		Telefono <input type="text" name="Itelefono" id="Itelefono" value="" size="" />
		Email <input type="text" name="Iemail" id="Iemail" value="" size="" />
		</p>
		<p style="text-align: center;">
		<input type="button" value="Agregar" class="botones" onClick="comprobar_campos()"/>
		</p>
		</form>
		</div>
	</div>
		<!-- FIN AGREGAR PROVEEDORES -->

		<!-- INICIO MODIFICAR PROVEEDORES -->
	<br /><br />
		<a href="#" onClick="mostrar_actualizar_proveedor()" id="boton_actualizar_proveedor">Modificar proveedor <img src="img/actualizar.png" width="20px" height="20px" style="vertical-align: middle;"/></a>
	  <div class="invisible" id="actualizar_proveedor">
	    <div class="agregados">
		<span style="float: right;"><input  class="botonesrojos" type="button" value="X" onClick="ocultar_actualizar_proveedor()" /></span>
		<b>Actualizar Proveedor: </b>
		<table>
	
		<?php while($filas_resultado_proveedores = $resultado_proveedores->fetch_assoc()) { ?>
		  
			<form method="post" action="proveedor.php" name="formactualizar">
		
      		<tr class="negro">
			<td>Codigo</td>
			<td>Nombre</td>
			<td>NIF/CIF</td>
			<td>Direccion</td>
			<td>Localidad</td>
		  </tr>
		  <tr class="negro">
			<td>
			<input type="text" value="<?php echo $filas_resultado_proveedores['CodPro']?>" name="CodPro" size="6" readonly="readonly"/>
			</td>

			<td>			
			<input type="text" value="<?php echo $filas_resultado_proveedores['NomPro']?>" name="NomPro"/>
			</td>

			<td>			
			<input type="text" value="<?php echo $filas_resultado_proveedores['NIF_CIF']?>" name="NIF_CIF" size="8"/>
			</td>			

			<td>
			<input type="text" value="<?php echo $filas_resultado_proveedores['direccion']?>" name="direccion" size="10"/>
			</td>
			
			<td>	
			<input type="text" value="<?php echo $filas_resultado_proveedores['localidad']?>" name="localidad" size="10"/>
			</td>			
		  </tr>
		 <tr class="negro">
			<td>Pais</td>
			<td>Provincia</td>
			<td>Telefono</td>
			<td>Email</td>
			<td></td>	
		   </tr>
		 <tr class="negro">
			<td>
			<input type="text" value="<?php echo $filas_resultado_proveedores['pais']?>" name="pais" size="2"/>
			</td>
			<td>		
			<input type="text" value="<?php echo $filas_resultado_proveedores['provincia']?>" name="provincia" size="10"/>
			
			</td>
			<td>
			<input type="text" value="<?php echo $filas_resultado_proveedores['telefono']?>" name="telefono" size="10" />
			</td>
			<td>
			<input type="text" value="<?php echo $filas_resultado_proveedores['email']?>" name="email" size="10"/>
			</td>
			<td>
			<input type="submit" value="actualizar" class="botones"/>
			</td>
			
			 </form>
		   </tr>
		   <tr>
			<td colspan="5"><hr /></td>
   		  </tr>
		<?php } ?>
		
		</table>
	     
          </div>
	</div>
	<!-- FIN MODIFICAR PROVEEDORES -->

	<!-- INICIO BORRAR PROVEEDORES -->
	
	<br /><br />
	<a href="#" onClick="mostrar_eliminar_proveedor()" id="boton_eliminar_proveedor">Eliminar proveedor <img src="img/borrar.png" width="20px" height="20px" style="vertical-align: middle;"/></a>
	  <div class="invisible" id="eliminar_proveedor">
	    <div class="agregados">
		<span style="float: right;"><input  class="botonesrojos" type="button" value="X" onClick="ocultar_eliminar_proveedor()" /></span>
		<b>Eliminar Proveedor: (solo los que no tienen articulos relacionados)</b>
		<form action="proveedor.php" method="post" name="borrar_proveedor">		
		<select name="BCodPro" onChange="desconfirmar_borrar_proveedor()">
		<?php while($filas_resultado_proveedores2 = $resultado_proveedores2->fetch_assoc()) { ?>
			<option value="<?php echo $filas_resultado_proveedores2['CodPro']?>"><?php echo $filas_resultado_proveedores2['CodPro']?> (<?php echo $filas_resultado_proveedores2['NomPro']?>)</option>	
		<?php } ?>
		</select>
		<a href="#" onClick="confirmar_borrar_proveedor()">Eliminar <img src="img/borrar.png" width="20px" height="20px" style="vertical-align: middle;"/></a>
		<p class="invisible" id="confirmar_borrar_proveedor">
		&iquest;Realmente desea elimiar el proveedor? Esta accion es irreversible.
		<input type="button" onClick="desconfirmar_borrar_proveedor()" value="NO, volver" class="botones"/> <input type="submit" value="SI, Eliminar" class="botonesrojos"/>
		<p>
		</form>
	  </div>
	</div>
	<!-- FIN BORRAR PROVEEDORES -->
<!-- FIN PROVEEDORES -->	
	<hr />
<!-- BANCOS-->
		<p><b>Bancos</b></p>
	<!-- INICIO AGREGAR BANCO -->
	<a href="#" onClick="mostrar_nuevo_banco()" id="boton_nuevo_banco">Nuevo banco <img src="img/nuevo.png" width="20px" height="20px" style="vertical-align: middle;"/></a>
	<div class="invisible" id="nuevo_banco">
		<div class="agregados">
		<span style="float: right;"><input  class="botonesrojos" type="button" value="X" onClick="ocultar_nuevo_banco()" /></span>
		<b>Nueva Banco: </b>
		<form name="agregar_banco" id="agregar_banco" method="post" action="proveedor.php">
		<p>
		Nombre <input type="text" name="Inombre" id="Inombre" value="" size="" />
		</p>
		<p style="text-align: center;">
		<input type="button" value="Agregar" class="botones" onClick="comprobar_campos_banco()"/>
		</p>
		</form>
		</div>
	</div>
		<!-- FIN AGREGAR BANCO -->

		<!-- INICIO MODIFICAR BANCO -->
	<br /><br />
		<a href="#" onClick="mostrar_actualizar_banco()" id="boton_actualizar_banco">Modificar banco <img src="img/actualizar.png" width="20px" height="20px" style="vertical-align: middle;"/></a>
	  <div class="invisible" id="actualizar_banco">
	    <div class="agregados">
		<span style="float: right;"><input  class="botonesrojos" type="button" value="X" onClick="ocultar_actualizar_banco()" /></span>
		<b>Actualizar Banco: </b>
		<table>
		   <tr>
			<td>Codigo</td>
			<td>Nombre</td>
			<td></td>
		   </tr>
		<?php while($filas_resultado_bancos3 = $resultado_bancos3->fetch_assoc()) { ?>
		   <tr>
			<form method="post" action="proveedor.php" name="formactualizar_banco">
			<td>
			<input type="text" value="<?php echo $filas_resultado_bancos3['CodBan']?>" name="CodBan" size="6" readonly="readonly"/>
			</td>

			
			<td>	
			<input type="text" value="<?php echo $filas_resultado_bancos3['nombre']?>" name="nombre"/>
			</td>
			
			<td>
			<input type="submit" value="actualizar" class="botones"/>
			</td>	
			</form>			
		   </tr>
		  
		<?php } ?>
		</table>
	     
          </div>
	</div>
	<!-- FIN MODIFICAR BANCO -->

	<!-- INICIO BORRAR BANCO -->
	
	<br /><br />
	<a href="#" onClick="mostrar_eliminar_banco()" id="boton_eliminar_banco">Eliminar banco <img src="img/borrar.png" width="20px" height="20px" style="vertical-align: middle;"/></a>
	  <div class="invisible" id="eliminar_banco">
	    <div class="agregados">
		<span style="float: right;"><input  class="botonesrojos" type="button" value="X" onClick="ocultar_eliminar_banco()" /></span>
		<b>Eliminar Banco: (solo los que no tienen cuentas relacionadas)</b>
		<form action="proveedor.php" method="post" name="borrar_banco">		
		<select name="BCodBan" onChange="desconfirmar_borrar_banco()">
		<?php while($filas_resultado_bancos2 = $resultado_bancos2->fetch_assoc()) { ?>
			<option value="<?php echo $filas_resultado_bancos2['CodBan']?>"><?php echo $filas_resultado_bancos2['CodBan']?> (<?php echo $filas_resultado_bancos2['nombre']?>)</option>	
		<?php } ?>
		</select>
		<a href="#"  onclick="confirmar_borrar_banco()">Eliminar <img src="img/borrar.png" width="20px" height="20px" style="vertical-align: middle;"/></a>
		<p class="invisible" id="confirmar_borrar_banco">
		&iquest;Realmente desea elimiar el banco? Esta accion es irreversible.
		<input type="button" onClick="desconfirmar_borrar_banco()" value="NO, volver" class="botones"/> <input type="submit" value="SI, Eliminar" class="botonesrojos"/>
		<p>
		</form>
	  </div>
	</div>
	<!-- FIN BORRAR BANCO -->
<!-- FIN BANCO -->	
<!-- FIN BANCOS -->
<hr />


<!-- INICIO CUENTAS-->
	<!-- CUENTAS-->
		<p><b>Cuentas</b></p>
	<!-- INICIO AGREGAR CUENTA -->
	<a href="#" onClick="mostrar_nuevo_cuenta()" id="boton_nuevo_cuenta">Nueva cuenta <img src="img/nuevo.png" width="20px" height="20px" style="vertical-align: middle;"/></a>
	<div class="invisible" id="nuevo_cuenta">
		<div class="agregados">
		<span style="float: right;"><input  class="botonesrojos" type="button" value="X" onClick="ocultar_nuevo_cuenta()" /></span>
		<b>Nueva Cuenta: </b>
		<form name="agregar_cuenta" id="agregar_cuenta" method="post" action="proveedor.php">
		Proveedor: <select name="ICodPro2">
		<?php while($filas_resultado_proveedoresC = $resultado_proveedoresC->fetch_assoc()) { ?>
			<option value="<?php echo $filas_resultado_proveedoresC['CodPro']?>"><?php echo $filas_resultado_proveedoresC['CodPro']?> (<?php echo $filas_resultado_proveedoresC['NomPro']?>)</option>	
		<?php } ?>
		</select>
		Banco: <select name="ICodBan2">
		<?php while($filas_resultado_bancosC = $resultado_bancosC->fetch_assoc()) { ?>
		<option value="<?php echo $filas_resultado_bancosC['CodBan']?>"><?php echo $filas_resultado_bancosC['CodBan']?> (<?php echo $filas_resultado_bancosC['nombre']?>)</option>	
		<?php } ?>
		</select>
		Numero de cuenta: <input type="text" name="INumCue"  id="INumCue" ></input>
		<p style="text-align: center;">
		<input type="button" value="Agregar" class="botones" onClick="comprobar_campos_cuenta()"/>
		</p>
		</form>
		</div>
	</div>
		<!-- FIN AGREGAR CUENTA -->

	<!-- INICIO BORRAR CUENTA -->
	
	<br /><br />
	<a href="#" onClick="mostrar_eliminar_cuenta()" id="boton_eliminar_cuenta">Eliminar cuenta <img src="img/borrar.png" width="20px" height="20px" style="vertical-align: middle;"/></a>
	  <div class="invisible" id="eliminar_cuenta">
	    <div class="agregados">
		<span style="float: right;"><input  class="botonesrojos" type="button" value="X" onClick="ocultar_eliminar_cuenta()" /></span>
		<b>Eliminar Cuenta: </b>
		<form action="proveedor.php" method="post" name="borrar_cuenta">		
		<select name="BNumCue" onChange="desconfirmar_borrar_cuenta()">
		<?php while($filas_resultado_cuentas2 = $resultado_cuentas2->fetch_assoc()) { ?>
			<option value="<?php echo $filas_resultado_cuentas2['NumCue']?>"><?php echo $filas_resultado_cuentas2['nombre']?> - <?php echo $filas_resultado_cuentas2['NomPro']?> - <?php echo $filas_resultado_cuentas2['NumCue']?></option>	
		<?php } ?>
		</select>
		<a href="#"  onclick="confirmar_borrar_cuenta()">Eliminar <img src="img/borrar.png" width="20px" height="20px" style="vertical-align: middle;"/></a>
		<p class="invisible" id="confirmar_borrar_cuenta">
		&iquest;Realmente desea elimiar la cuenta? Esta accion es irreversible.
		<input type="button" onClick="desconfirmar_borrar_cuenta()" value="NO, volver" class="botones"/> <input type="submit" value="SI, Eliminar" class="botonesrojos"/>
		<p>
		</form>
	  </div>
	</div>
	<!-- FIN BORRAR CUENTA -->
<!-- FIN CUENTA -->	
<!-- FIN CUENTAS -->



<!--FIN CUENTAS -->
</div>
</body>
</html>
