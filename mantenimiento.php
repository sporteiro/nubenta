<?php
declare(strict_types=1);
require_once('clases/cargador.php');
$sesion = new sesion();
$conectarBD = new conexion();
$conn = $conectarBD->getConexion();

if (!$sesion->conectado) {
	header("location: index.php");
}

//CONVERTIR ALBARANES EN FACTURAS
//CONSULTAR LOS ALBARANES
$stmt = $conn->prepare("SELECT * FROM facpro WHERE albaran_factura='albaran' AND CodUsu = ?");
$stmt->bind_param('s', $sesion->CodUsu);
$stmt->execute();
$resultado_facpro = $stmt->get_result();

//CONVERTIR
if (isset($_POST["CodFac"])) {
	$CodFac = $conn->real_escape_string($_POST['CodFac']);
	$NumeroFactura = $conn->real_escape_string($_POST['NumeroFactura']);
	$stmt2 = $conn->prepare("UPDATE facpro SET NumeroFactura = ?, albaran_factura = 'factura' WHERE CodFac = ? AND CodUsu = ?");
	$stmt2->bind_param('sss', $NumeroFactura, $CodFac, $sesion->CodUsu);
	$stmt2->execute();
	$stmt2->close();
	header("Location: mantenimiento.php");
}

//CONSULTA EMPRESA PARA ACTUALIZARLA
$stmt3 = $conn->prepare("SELECT * FROM datos_empresa WHERE CodUsu = ?");
$stmt3->bind_param('s', $sesion->CodUsu);
$stmt3->execute();
$resultado_empresa = $stmt3->get_result();
$filas_empresa = $resultado_empresa->fetch_assoc();

//ACTUALIZAR
if (isset($_POST["nombre"])) {
	$nombrempresa = $conn->real_escape_string($_POST["nombre"]);
	$NIF_CIF = $conn->real_escape_string($_POST['NIF_CIF']);
	$direccion = $conn->real_escape_string($_POST['direccion']);
	$telefono = $conn->real_escape_string($_POST['telefono']);
	$ticket_grande = $conn->real_escape_string($_POST['ticket_grande']);
	$ticket_chiquito = $conn->real_escape_string($_POST['ticket_chiquito']);
	$moneda = $conn->real_escape_string($_POST['moneda'] ?? '');

	$stmt4 = $conn->prepare("UPDATE datos_empresa SET nombre = ?, NIF_CIF = ?, direccion = ?, telefono = ?, ticket_grande = ?, ticket_chiquito = ? WHERE CodUsu = ?");
	$stmt4->bind_param('sssssss', $nombrempresa, $NIF_CIF, $direccion, $telefono, $ticket_grande, $ticket_chiquito, $sesion->CodUsu);
	$stmt4->execute();
	$stmt4->close();
	header("Location: mantenimiento.php");
}

//CONSULTA USUARIO PARA MODIFICAR
$stmt5 = $conn->prepare("SELECT * FROM andrea WHERE CodUsu = ?");
$stmt5->bind_param('s', $sesion->CodUsu);
$stmt5->execute();
$resultado_usuario = $stmt5->get_result();
$filas_usuario = $resultado_usuario->fetch_assoc();

//ACTUALIZAR
if (isset($_POST["nusuario"])) {
	$nusuario = $conn->real_escape_string($_POST["nusuario"]);
	$apellido = $conn->real_escape_string($_POST['apellido']);
	$email = $conn->real_escape_string($_POST['email']);
	$contrasena = $conn->real_escape_string($_POST['contrasena']);
	$moneda_usuario = $conn->real_escape_string($_POST['moneda']);

	$stmt6 = $conn->prepare("UPDATE andrea SET nombre = ?, apellido = ?, email = ?, contrasena = ?, moneda = ? WHERE CodUsu = ?");
	$stmt6->bind_param('ssssss', $nusuario, $apellido, $email, $contrasena, $moneda_usuario, $sesion->CodUsu);
	$stmt6->execute();
	$stmt6->close();
	header("Location: mantenimiento.php");
}

//SUBIDA DE IMAGENES
if (isset($_POST["oculto_imagen"]))  {
$rutaimagenes='img/';
	try {
		if (($_FILES["imagen"]["type"] == "image/jpeg") && ($_FILES["imagen"]["size"] < 100000))  {
			if ($_FILES["imagen"]["error"] > 0) {
    				echo "Error";
    			}
  			else	{
				move_uploaded_file($_FILES["imagen"]["tmp_name"],
      				$rutaimagenes.$_FILES["imagen"]["name"]);
    			}
		}
  	}
	catch (Exception $e) {
		echo 'error'.$e;
	}
}
?>
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="estilo.css" />
<script type="text/javascript" src="js/articulo.js"></script>
<script type="text/javascript" src="js/comprobar_mantenimiento.php"></script>
</head>
<body style="background-color: black;">
<br />
<div class="iframes">

  <!-- Datos empresa-->
    
    <div>
<a href="#" onClick="mostrar_nuevo_familia()" id="boton_nuevo_familia">Cambiar datos de la empresa</a>
	  <div class="invisible" id="nuevo_familia">
	    <div class="agregados" >
		<span style="float: right;"><input  class="botonesrojos" type="button" value="X" onClick="ocultar_nuevo_familia()" /></span>
		<b>Datos de la empresa: </b>
			<table>
            	<form action="mantenimiento.php" method="post" name="cambiarEmpresa" id="cambiarEmpresa">
				<tr class="negro" >
					<td>Nombre de la empresa</td> 	
                    			<td>NIF/CIF </td>
                    			<td>Direccion </td>	
                   			 <td>Telefono</td> 	
				</tr>
                		<tr>
		 			<td><input type="text" name="nombre" value="<?php echo $filas_empresa['nombre'] ?>"/></td> 	
                 			<td><input type="text" name="NIF_CIF" value="<?php echo $filas_empresa['NIF_CIF'] ?>"/></td>
                    			<td><input type="text" name="direccion" value="<?php echo $filas_empresa['direccion'] ?>" /></td>	
                    			<td><input type="text" name="telefono" value="<?php echo $filas_empresa['telefono'] ?>"/></td> 
               			</tr>
				<tr>
                    			<td>Informacion del ticket (letra grande)</td> 	
                    			<td>Informacion del ticket (letra mas chica)</td>
		    			<td></td>
               			</tr>
               			<tr>	
                    			<td><textarea cols="14" rows="4" name="ticket_grande"><?php echo $filas_empresa['ticket_grande'] ?></textarea></td> 	
                    			<td><textarea cols="14" rows="4" type="text" name="ticket_chiquito"><?php echo $filas_empresa['ticket_chiquito'] ?></textarea></td>
		    			<td></td>
			

                    			<td><input type="submit" value="Modificar" class="botones"/></td>
				</tr>
		    	</form>
               </table>
          </div>
      </div>
	</div>
    <br />
    
    <!--FIN Datos empresa -->

 <!-- Datos empresa-->
    
    <div>
<a href="#" onClick="mostrar_nuevo_articulo()" id="boton_nuevo_articulo">Cambiar datos de usuario</a>
	  <div class="invisible" id="nuevo_articulo">
	    <div class="agregados" >
		<span style="float: right;"><input  class="botonesrojos" type="button" value="X" onClick="ocultar_nuevo_articulo()" /></span>
		<b>Datos de usuario: </b><i>(Los cambios tendran efecto en el proximo inicio de sesion)</i>
			<table>
            	<form action="mantenimiento.php" method="post" name="cambiarUsuario" id="cambiarUsuario">
				<tr class="negro" >
					<td>Nombre </td>
					<td>Apellido </td>	
			                <td>Email</td> 	
				</tr>
                		<tr>
		   		 	<td><input type="text" name="nusuario" value="<?php echo $filas_usuario['nombre'] ?>"/></td> 	
                    			<td><input type="text" name="apellido" value="<?php echo $filas_usuario['apellido'] ?>"/></td>
                    			<td><input type="text" name="email" value="<?php echo $filas_usuario['email'] ?>" /></td>	
		               </tr>
			       <tr>                 	   	
					<td>Nueva contrase&ntilde;a</td> 	
		    			<td>Moneda</td>
                    			<td></td>
               		       </tr>
               		       <tr>	
                    		 <td><input type="password" name="contrasena" value="<?php echo $filas_usuario['contrasena']?>" /></td>	
	            		 <td>
                    		<select name="moneda">
					<option value="<?php echo $filas_usuario['moneda'] ?>" selected="selected">
						<?php echo $filas_usuario['moneda'] ?>
					</option>			
					<option value="&#36;">&#36; (Peso)</option>
					<option value="US&#36;">US&#36; (Dolar)</option>
					<option value="&euro;">&euro; (Euro)</option>
		   		 </select>
		   		 </td>
                    		 <td><input type="submit" value="Modificar" class="botones"/></td>
				</tr>
		    	</form>
               </table>
          </div>
      </div>
	</div>
    <br />
    
    <!--FIN Datos empresa -->
<!-- INICIO Imagenes -->

<a href="#" onClick="mostrar_actualizar_familia()" id="boton_actualizar_familia">Subir imagenes de articulos</a>
	  <div class="invisible" id="actualizar_familia">
	    <div class="agregados">
		<span style="float: right;"><input  class="botonesrojos" type="button" value="X" onClick="ocultar_actualizar_familia()" /></span>
		<b>Subir imagenes de articulos</b>
		<br />
		<i>Cada imagen debera tener el mismo nombre que el numero de articulo (con todos los ceros) y extension jpg.<br />
		Por ejemplo: 000<?php echo $sesion->CodUsu+100?>.jpg</i>
		<form method="post" action="mantenimiento.php" name="formimagen" enctype="multipart/form-data">
			
			<input type="file" name="imagen" class="botones" id="imagen"/>
			<input type="hidden" name="oculto_imagen"/>
			<input type="submit" value="Subir" class="botones"/>		
		</form>
	    </div>
	</div>
<br />
<br />
<!-- FIN Imagenes -->
<div>
<a href="#" onClick="mostrar_actualizar_articulo()" id="boton_actualizar_articulo">Convertir Remito (o albaran) en Factura</a>
	  <div class="invisible" id="actualizar_articulo">
	    <div class="agregados">
		<span style="float: right;"><input  class="botonesrojos" type="button" value="X" onClick="ocultar_actualizar_articulo()" /></span>
		<b>Todos los Remitos: </b>

<?php   $repetido=-1; 
	 while ($filas_facpro= $resultado_facpro->fetch_assoc()) {
	         if ($repetido!= $filas_facpro['CodFac']) {  ?>
			   <?php if ($repetido!=-1) echo "</table><hr />" ?>
			<table>
				<tr class="negro">
				
				<td><b>Factura numero: <?php echo$filas_facpro['CodFac'];?> </b> </td>
				<td>Fecha: <?php echo$filas_facpro['fecha'];?></td>
				<td></td>							
				</tr>
				<tr><form action="mantenimiento.php" method="post" name="cambiar<?php echo $filas_facpro['CodFac'];?>" id="cambiar<?php echo $filas_facpro['CodFac'];?>">
                <td>Numero Remito <input type="text" value="<?php echo $filas_facpro['NumeroFactura'];?>" readonly="readonly"/><input type="hidden" value="<?php echo $filas_facpro['CodFac'];?>"  name="CodFac"/></td>
                <td>Numero Factura <input type="text" name="NumeroFactura" id="N<?php echo $filas_facpro['CodFac'];?>" value="" size="9" maxlength="9" /></td>
                <td><input type="button" onClick="comprobar_factura<?php echo $filas_facpro['CodFac'];?>()" value="Convertir en Factura" class="botones"/></td>
                </form>
                </tr>
				<tr>
				<td>Articulo</td>
				<td>Nombre</td>
				<td>Cantidad</td>
				</tr>
		
				<?php $repetido=$filas_facpro['CodFac']; } ?>
				<tr class="fondonegro">
				<td><?php echo $filas_facpro['CodArt'];?></td>
				<td><?php echo $filas_facpro['NomArt'];?> </td>
				<td><?php echo $filas_facpro['cantidad'];?></td>
						
				</tr>
		<?php } ?><?php if ($repetido!=-1) echo "</table><hr />" ?>
        </div>
      </div>
	</div>
    <br />
  
</div>
</body>
</html>
