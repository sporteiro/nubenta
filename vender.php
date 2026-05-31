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
if (isset($_POST['CodArt']))  {
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

//INSERTAR EN LA TABLA VENTAS LA COMPRA PARCIAL, DESPUES SE BORRARA
if (isset($_POST["nombre"]) && (isset($_POST["cantidad"])) && ($_POST["cantidad"]!=0) )  {
	$numtic = $conn->real_escape_string($_POST['numtic']);
	$CodArt2 = $conn->real_escape_string($_POST['CodArt2']);
	$OrdMov = $conn->real_escape_string($_POST['OrdMov']);
	$nombre = $conn->real_escape_string($_POST['nombre']);
	$descripcion = $conn->real_escape_string($_POST['descripcion']);
	$cantidad = $conn->real_escape_string($_POST['cantidad']);
	$stock = $conn->real_escape_string($_POST['stock']);
	$precio = $conn->real_escape_string($_POST['precio']);
	$IVA = $conn->real_escape_string($_POST['IVA']);
	$fecha = $conn->real_escape_string($_POST['fecha']);
	$hora = $conn->real_escape_string($_POST['hora']);

  	$stmt2 = $conn->prepare("INSERT INTO ventas (OrdMov,CodFac,CodArt,NomArt,DesArt,cantidad, stock, precio,IVA,fecha,hora,ForPag,entregado,CodUsu) VALUES (?, ?, ?, ?, ?, ?, ?-?, ?, ?, ?, ?, 'efectivo', 0, ?) ON DUPLICATE KEY UPDATE cantidad=cantidad+?");
	$stmt2->bind_param('ssssssssssssss', $OrdMov, $numtic, $CodArt2, $nombre, $descripcion, $cantidad, $stock, $cantidad, $precio, $IVA, $fecha, $hora, $sesion->CodUsu, $cantidad);
	$stmt2->execute();
	$stmt2->close();

	header("Location: vender.php");
}

//CONSULTO LOS ARTICULOS QUE ESTAN SIENDO ELEGIDOS PARA VENDERSE
$stmt3 = $conn->prepare("SELECT * FROM ventas WHERE CodUsu = ?");
$stmt3->bind_param('s', $sesion->CodUsu);
$stmt3->execute();
$resultado_venta = $stmt3->get_result();
$total_filas_venta = $resultado_venta->num_rows;

//BUSCO EL ULTIMO TICKET PARA DESPUES SUMARLE UNO AL INSERTARLO EN LA FACTURA
$stmt4 = $conn->prepare("SELECT CodFac FROM faccli WHERE CodUsu = ? ORDER BY CodFac DESC");
$stmt4->bind_param('s', $sesion->CodUsu);
$stmt4->execute();
$resultado_tiket = $stmt4->get_result();
$filas_tiket = $resultado_tiket->fetch_assoc();
$stmt4->close();

//DESCARTAR EL PEDIDO
if (isset($_POST["borrar"]))  {
  	$stmt5 = $conn->prepare("DELETE FROM ventas WHERE CodUsu = ?");
	$stmt5->bind_param('s', $sesion->CodUsu);
	$stmt5->execute();
	$stmt5->close();
	header("Location: vender.php");
}
?>

<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="estilo.css" />
</head>
<body style="background-color: black;">
<div class="iframes">
		<p><b>Efectuar Venta </b></p>
<!-- INICIO AGREGAR PRODUCTOS -->		

		<div id="agregar_producto">
		
		
		<form action="vender.php" method="post">
				<p>Codigo Producto: <input type="text" onChange="this.form.submit()" name="CodArt" value="<?php echo $articulo?>" onBlur="if(this.value=='') this.value='Buscar...';" onFocus="if(this.value=='Buscar...') this.value='';"/>
		</form>

		<?php if ($total_filas_resultado) { ?>
		<form action="vender.php" method="post">
		<input type="hidden" name="CodArt2" value="<?php echo $filas_resultado['CodArt'];?>"/>
		<?php
		//calcular numero ticket
			$rangoT=$sesion->CodUsu*1000;
			if (!$filas_tiket['CodFac']) {
				$numtic=$rangoT+1;
				echo $numtic;			
			}
			else if (($filas_tiket['CodFac'])<($rangoT+1000)) {
				$numtic=$filas_tiket['CodFac']+1;
				echo $numtic;
			}
			else {
			echo "Se ha alcanzado el numero maximo de Compras";
				$numtic=$rangoT;
				echo $numtic;
			} ?>
		<input type="hidden"  name="numtic" value="<?php echo $numtic?>"/>
		<input type="hidden"  name="OrdMov" value="<?php echo $filas_resultado['OrdMov']+1?>"/>
		<p>
		<span class="nada"><a href="img/<?php echo $filas_resultado['CodArt'];?>.jpg" target="_blank" title="Ampliar"><img src="img/<?php echo $filas_resultado['CodArt'];?>.jpg"  height="74px" width="74px" style="color:#FFF;" alt="<?php echo $filas_resultado['NomArt'];?>"/></a></span>
		Nombre: <input type="text" name="nombre" value="<?php echo $filas_resultado['NomArt'];?>"/> 
		Descripcion: <input type="text" name="descripcion" value="<?php echo $filas_resultado['DesArt'];?>"/> 
		</p>
		<p>
		Base Imponible: <input type="text" size="6" maxlength="6"name="precio" value="<?php echo $filas_resultado['precio'];?>"/><?php echo $sesion->moneda;?>&nbsp;&nbsp;&nbsp;&nbsp;
		IVA: <input type="text" value="18" size="3" name="IVA" id="IVA"/>%&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="hidden" value="<?php echo $filas_resultado['cantidad'];?>" name="stock"/>		
		Cantidad: 
		<select id="cantidad" name="cantidad">
			<?php for($z=0;$z<$filas_resultado['cantidad'];$z++) { ?>
 				<option><?php echo $z+1;?>
				</option>
			
			<?php } ?>
		</select>			
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
			<b>Productos seleccionados para venderse</b>
			</p>			
			<table>	
				<tr class="negro">
					<td>Lista</td> 
                    <td>Codigo Producto</td> 
                    <td>Nombre</td> 
                    <td>Base imponible </td> 
                    <td>IVA </td>
                    <td> Cantidad </td> 
                    <td>Total producto</td>
				</tr>
				<?php	$mas=1;
					$total=0; 
					while ($filas_resultado_venta = $resultado_venta->fetch_assoc()) { ?>
				<tr>
					<td><?php echo $mas++ ?></td> 
					<td><?php echo $filas_resultado_venta['CodArt']?></td> 
					<td><?php echo $filas_resultado_venta['NomArt']?></td> 
					<td><?php echo $filas_resultado_venta['precio']?><?php echo $sesion->moneda;?></td> 
					<td>
					<?php $elIVA=number_format((($filas_resultado_venta['precio'])*($filas_resultado_venta['IVA'])/100),2);?>
					<?php echo $elIVA?><?php echo $sesion->moneda;?></td> 
					<td><?php echo $filas_resultado_venta['cantidad']?></td> 
					<td><?php echo number_format((($filas_resultado_venta['precio']+$elIVA)*($filas_resultado_venta['cantidad'])),2)?><?php echo $sesion->moneda;?></td>
				</tr>
					
				<?php  $total=$total+($filas_resultado_venta['precio']+$elIVA)*($filas_resultado_venta['cantidad']);  } ?>	

				<tr class="negro">						
					<td colspan="5"></td><td>Total a abonar:</td> <td><b><?php echo number_format($total, 2)?><?php echo $sesion->moneda;?></b></td>
				</tr>
			</table>
			<br />
			<div style="text-align: center;">
				<form action="vender.php" method="post">
					<input type="hidden" name="borrar" value=""/>
					<input type="submit" value="Anular la venta" class="botonesrojos" style="width: 200px;"/>
					<a href="faccli.php" class="botones" style="width: 200px; display: inline-block; text-align: center;">Efectuar la venta</a>
				</form>

			</div>
		</div>
		<?php  } ?>
</div>
</body>
</html>
