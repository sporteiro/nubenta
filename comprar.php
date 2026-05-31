<?php
declare(strict_types=1);
require_once('clases/cargador.php');
$sesion = new sesion();
$conectarBD = new conexion();
$conn = $conectarBD->getConexion();

if (!$sesion->conectado) {
	header("location: index.php");
}

//BUSCAR LOS DATOS DEL ARTICULO POR SU CODIGO ENVIADO POR POST
if (isset($_POST['CodArt']) && $_POST['CodArt'] !== null && $_POST['CodArt'] !== 'Buscar...')  {
	$articulo = $conn->real_escape_string($_POST['CodArt']);
}
else {
	$articulo = 'Buscar...';
}

$stmt = $conn->prepare("SELECT * FROM articulos WHERE CodArt = ? AND CodUsu = ?");
$stmt->bind_param('ss', $articulo, $sesion->CodUsu);
$stmt->execute();
$resultado = $stmt->get_result();
$filas_resultado = $resultado->fetch_assoc();
$total_filas_resultado = $resultado->num_rows;
$stmt->close();

//INSERTAR EN LA TABLA COMPRAS LA COMPRA PARCIAL, DESPUES SE BORRARA
if (isset($_POST["nombre"]) && (isset($_POST["cantidad"])) && ($_POST["cantidad"]!=0) )  {
	$numtic = $conn->real_escape_string($_POST['numtic']);
	$CodArt2 = $conn->real_escape_string($_POST['CodArt2']);
	$OrdMov = $conn->real_escape_string($_POST['OrdMov']);
	$nombre = $conn->real_escape_string($_POST['nombre']);
	$descripcion = $conn->real_escape_string($_POST['descripcion']);
	$cantidad = $conn->real_escape_string($_POST['cantidad']);
	$stock = $conn->real_escape_string($_POST['stock']);
	$precio = $conn->real_escape_string($_POST['precio']);
	$PreCom = $conn->real_escape_string($_POST['PreCom']);
	$descuento = $conn->real_escape_string($_POST['descuento']);
	$IVA = $conn->real_escape_string($_POST['IVA']);
	$recargo = $conn->real_escape_string($_POST['recargo']);
	$fecha = $conn->real_escape_string($_POST['fecha']);
	$hora = $conn->real_escape_string($_POST['hora']);
	$numero_factura = '';
	$cantidad_total = $cantidad + $stock;
	$forpag = 'efectivo';
	$albaran_factura = 'albaran';

  	$stmt2 = $conn->prepare("INSERT INTO compras (NumeroFactura,CodFac,CodArt,OrdMov,NomArt,DesArt,cantidad,stock,precio,PreCom,descuento,IVA,recargo,fecha,hora,ForPag,albaran_factura,CodUsu) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE cantidad=cantidad+?");
	$stmt2->bind_param('sssssssssssssssssss', $numero_factura, $numtic, $CodArt2, $OrdMov, $nombre, $descripcion, $cantidad_total, $stock, $precio, $PreCom, $descuento, $IVA, $recargo, $fecha, $hora, $forpag, $albaran_factura, $sesion->CodUsu, $cantidad);
	$stmt2->execute();
	$stmt2->close();

	header("Location: comprar.php");
}

//CONSULTO LOS ARTICULOS QUE ESTAN SIENDO ELEGIDOS PARA COMPRARSE
$stmt3 = $conn->prepare("SELECT * FROM compras WHERE CodUsu = ?");
$stmt3->bind_param('s', $sesion->CodUsu);
$stmt3->execute();
$resultado_venta = $stmt3->get_result();
$total_filas_venta = $resultado_venta->num_rows;

//BUSCO EL ULTIMO TICKET PARA DESPUES SUMARLE UNO AL INSERTARLO EN LA FACTURA
$stmt4 = $conn->prepare("SELECT CodFac FROM facpro WHERE CodUsu = ? ORDER BY CodFac DESC");
$stmt4->bind_param('s', $sesion->CodUsu);
$stmt4->execute();
$resultado_tiket = $stmt4->get_result();
$filas_tiket = $resultado_tiket->fetch_assoc();
$stmt4->close();

//DESCARTAR EL PEDIDO
if (isset($_POST["borrar"]))  {
  	$stmt5 = $conn->prepare("DELETE FROM compras WHERE CodUsu = ?");
	$stmt5->bind_param('s', $sesion->CodUsu);
	$stmt5->execute();
	$stmt5->close();
	header("Location: comprar.php");
}

//buscar articulos por proveedor
//CONSULTA DE PROVEEDORES
$stmt6 = $conn->prepare("SELECT * FROM proveedores WHERE CodUsu = ?");
$stmt6->bind_param('s', $sesion->CodUsu);
$stmt6->execute();
$resultado_proveedores = $stmt6->get_result();

//BUSCAR ARTICULOS
if (isset($_GET['buscar']))  {
	$articulo2 = $conn->real_escape_string($_GET['buscar']);
}
else {
	$articulo2 = 'Buscar...';
}

$stmt7 = $conn->prepare("SELECT * FROM articulos WHERE CodPro = ? AND CodUsu = ?");
$stmt7->bind_param('ss', $articulo2, $sesion->CodUsu);
$stmt7->execute();
$resultado_articulos_busca = $stmt7->get_result();
$total_filas_resultado_articulos_busca = $resultado_articulos_busca->num_rows;
$stmt7->close();
?>

<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Kamalakshi ::: Usado por <?php echo $_SESSION['nombreusuario'];?></title>
<link rel="stylesheet" type="text/css" href="estilo.css" />
<script type="text/javascript" src="js/buscar_foto.php"></script>
</head>
<body style="background-color: #000022;">
<div class="iframes" style="background-color: #000022;">
		<p><b>Efectuar Compra </b></p>
<!-- INICIO AGREGAR PRODUCTOS -->		

		<div id="agregar_producto">
        <!-- Buscar por proveedores -->
        
		<div style="float:right">
       	<form action="comprar.php?buscar=" method="get">
        <select onChange="this.form.submit()" name="buscar" id="buscar">
        	<option value="Buscar" selected="selected">Buscar articulos de un proveedor</option>
        	<?php while($filas_resultado_proveedores= $resultado_proveedores->fetch_assoc()) { ?>
		   <option value="<?php echo $filas_resultado_proveedores['CodPro']?>"><?php echo $filas_resultado_proveedores['NomPro']?></option>
			<?php } ?>
        </select>
		</form><br />
        <?php while ($filas_resultado_articulos_busca= $resultado_articulos_busca->fetch_assoc()) { ?>
        	<div style="float:left">
            <form>
			 <input type="hidden" value="<?php echo $filas_resultado_articulos_busca['CodArt'];?>" name="foto<?php echo $filas_resultado_articulos_busca['CodArt'];?>" id="foto<?php echo $filas_resultado_articulos_busca['CodArt'];?>"/>
              <?php echo $filas_resultado_articulos_busca['CodArt'];?><br />
			  <span class="nada"><a href="#" onClick="buscarFoto<?php echo $filas_resultado_articulos_busca['CodArt'];?>()" ><img src="img/<?php echo  $filas_resultado_articulos_busca['CodArt'];?>.jpg" height="64px" width="64px" style="color:#FFF;" alt="<?php echo $filas_resultado_articulos_busca['NomArt'];?>" title="<?php echo $filas_resultado_articulos_busca['NomArt'];?>---<?php echo $filas_resultado_articulos_busca['DesArt'];?>"/></a></span>&nbsp;
              
               </form>
              </div>
             
        		<?php } ?>
        </div>
   <!--Fin Buscar por proveedores -->
        

        <div style="clear:both"></div>
		<form action="comprar.php" method="post" id="form_buscar">
				<p>Codigo Producto: <input type="text" onChange="this.form.submit()" name="CodArt" id="CodArt" value="<?php echo $articulo?>" onBlur="if(this.value=='') this.value='Buscar...';" onFocus="if(this.value=='Buscar...') this.value='';"/>
		</form>

		<?php if ($total_filas_resultado) { ?>
		<form action="comprar.php" method="post">
		<input type="hidden" name="CodArt2" value="<?php echo $filas_resultado['CodArt'];?>"/>
		<input type="hidden"  name="numtic" value="<?php echo ($filas_tiket && isset($filas_tiket['CodFac'])) ? $filas_tiket['CodFac']+1 : 1?>"/>
		<input type="hidden"  name="OrdMov" value="<?php echo $filas_resultado['OrdMov']+1?>"/>
		<p>
		Nombre: <input type="text" name="nombre" value="<?php echo $filas_resultado['NomArt'];?>"/>
		Descripcion: <input type="text" name="descripcion" value="<?php echo $filas_resultado['DesArt'];?>" size="36"/>
		Foto: <span class="nada"><a href="img/<?php echo $filas_resultado['CodArt'];?>.jpg" target="_blank" title="Ampliar"><img src="img/<?php echo $filas_resultado['CodArt'];?>.jpg"  height="74px" width="74px" style="color:#FFF;" alt="<?php echo $filas_resultado['NomArt'];?>"/></a></span>
		</p>
		<p>
		Base Imponible: <input type="text" size="6" maxlength="6" name="PreCom" value="<?php echo $filas_resultado['PreCom'];?>"/><?php echo $sesion->moneda;?> &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;
		Precio de venta: (sin IVA)<input type="text" size="6" maxlength="6" name="precio" value="<?php echo $filas_resultado['precio'];?>"/><?php echo $sesion->moneda;?>	
		</p>
		<p>
		Descuento: <input type="text" size="4" maxlength="4" name="descuento" value="0"/>%&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		IVA: <input type="text" value="18" size="3" name="IVA" id="IVA"/>%&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		Recargo de equivalencia: <input type="text" value="4" size="2" name="recargo" id="recargo"/>% &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		Cantidad: <input type="text" id="cantidad" name="cantidad" size="3" maxlength="5"/>
			<input type="hidden" value="<?php echo $filas_resultado['cantidad'];?>" name="stock"/>			
		</p>
		<input type="hidden" value="<?php echo date('Y-n-j');?>" name="fecha"/>
		<input type="hidden" value="<?php echo date('H:i'); ?>" name="hora"/>
	
		<p style="text-align: center;"> <input type="submit" value="Agregar" class="botones"/>
		</p>
		</form>	
		<?php } ?>
		</div>
		
		
		<hr />
<!-- FIN AGREGAR PRODUCTOS -->

<!-- INICIO PRODUCTOS AGREGADOS -->
		<?php if ($total_filas_venta) {?>
		<div id="productos_agregados">
			<p>
			<b>Productos seleccionados para Comprarse</b>
			</p>			
			<table>	
				<tr class="negro">
					<td>Lista</td> 
					<td>Codigo Producto</td> 
					<td>Nombre</td> 
					<td>Base imponible </td>
					<td>Descuento</td> 
					<td>IVA </td>
					<td>Rec Equivalencia </td>
					<td>Cantidad </td> 
					<td>Total producto</td>
				</tr>
				<?php	$mas=1;
					$total=0; 
					while ($filas_resultado_venta= $resultado_venta->fetch_assoc()) { ?>
				<tr>
					<td><?php echo $mas++ ?></td> 
					<td><?php echo $filas_resultado_venta['CodArt']?></td> 
					<td><?php echo $filas_resultado_venta['NomArt']?></td> 
					<td><?php echo $filas_resultado_venta['PreCom']?><?php echo $sesion->moneda;?></td>
					<td>
					<?php $eldescuento=($filas_resultado_venta['PreCom'])*($filas_resultado_venta['descuento'])/100;?>
					<?php echo number_format($eldescuento,2)?><?php echo $sesion->moneda;?></td>  
					<td>
					<?php $elIVA=($filas_resultado_venta['PreCom'])*($filas_resultado_venta['IVA'])/100;?>
					<?php echo number_format($elIVA,2)?><?php echo $sesion->moneda;?></td>
                    <td>
					<?php $elrecargo=($filas_resultado_venta['PreCom'])*($filas_resultado_venta['recargo'])/100;?>
					<?php echo number_format($elrecargo,2)?><?php echo $sesion->moneda;?></td>  
					<td><?php echo $filas_resultado_venta['cantidad']?></td> 
					<td>
					<?php echo number_format(($filas_resultado_venta['PreCom']+$elIVA+$elrecargo-($eldescuento))*($filas_resultado_venta['cantidad']),2)?><?php echo $sesion->moneda;?>
                    </td>
				</tr>
					
				<?php  $total=$total+($filas_resultado_venta['PreCom']+$elIVA+$elrecargo-($eldescuento))*($filas_resultado_venta['cantidad']);  } ?>

				<tr class="negro">						
					<td colspan="6"></td>
					<td colspan="2">Total a abonar:</td> 
					<td><b><?php echo number_format($total, 2)?><?php echo $sesion->moneda;?></b></td>
				</tr>
			</table>
			<br />
			<div style="text-align: center;">
				<form action="comprar.php" method="post">
					<input type="hidden" name="borrar" value=""/>
					<input type="submit" value="Anular la compra" class="botonesrojos" style="width: 200px;"/>
					<a href="facpro.php" class="botones" style="width: 200px; display: inline-block; text-align: center;">Efectuar la compra</a>
				</form>

			</div>
		</div>
		<?php  } ?>
</div>
</body>
</html>
