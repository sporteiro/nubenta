<?php
declare(strict_types=1);
require_once('clases/cargador.php');
$sesion = new sesion();
$conectarBD = new conexion();
$conn = $conectarBD->getConexion();

if (!$sesion->conectado) {
	header("location: index.php");
}

//CONSULTO LOS ULTIMOS ARTICULOS EN SER VENDIDOS
$otrocodigo = $conn->real_escape_string($_GET['otrocodigo'] ?? '');
$stmt = $conn->prepare("SELECT * FROM faccli WHERE CodFac = ? AND CodUsu = ?");
$stmt->bind_param('ss', $otrocodigo, $sesion->CodUsu);
$stmt->execute();
$resultado_venta = $stmt->get_result();

//CONSULTO EL CODIGO LA FECHA Y LA HORA LOS ULTIMOS ARTICULOS EN SER VENDIDOS
$stmt2 = $conn->prepare("SELECT CodFac,fecha,hora, ForPag, entregado FROM faccli WHERE CodFac = ? AND CodUsu = ?");
$stmt2->bind_param('ss', $otrocodigo, $sesion->CodUsu);
$stmt2->execute();
$resultado_venta_H = $stmt2->get_result();
$filas_resultado_venta_H = $resultado_venta_H->fetch_assoc();

//CONSULTO LOS DATOS DE LA EMPRESA
$stmt3 = $conn->prepare("SELECT UPPER(nombre) as grannombre,nombre,NIF_CIF,direccion,telefono,ticket_grande,ticket_chiquito from datos_empresa WHERE CodUsu = ?");
$stmt3->bind_param('s', $sesion->CodUsu);
$stmt3->execute();
$resultado_empresa = $stmt3->get_result();
$filas_resultado_empresa = $resultado_empresa->fetch_assoc();
?>
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="impresion.css" />
</head>
<body onLoad="window.print()">
<div class="iz">
	<div class="titulo">
	  <?php echo $filas_resultado_empresa['grannombre']?>
	</div>
	
<?php echo $filas_resultado_empresa['direccion']?><br />
NIF/CIF: <?php echo $filas_resultado_empresa['NIF_CIF']?> Tel&eacute;fono: <?php echo $filas_resultado_empresa['telefono']?><br />
Ticket: <?php echo $filas_resultado_venta_H['CodFac']?> <br />
Fecha: <?php $F=$filas_resultado_venta_H['fecha'];
	echo $F[8].$F[9].'/'.$F[5].$F[6].'/'.$F[0].$F[1].$F[2].$F[3]?>
 Hora:  <?php echo $filas_resultado_venta_H['hora'][0].$filas_resultado_venta_H['hora'][1].':'. $filas_resultado_venta_H['hora'][3].$filas_resultado_venta_H['hora'][4]?><br />
Venta realizada por: <?php echo$sesion->nombre;?>
<br /><br /><br />




	<table class="der">
		<tr>

			<td>L </td>
			<td>Producto</td> 
			<td>BaseImp </td>
			<td>IVA </td>
			<td>Can </td>
			<td>Total</td>
		</tr>
<?php	$mas=1;
	$total=0; 

	while ($filas_resultado_venta = $resultado_venta->fetch_assoc()) { ?>
		<tr>
			<td><?php echo $mas++ ?></td>
			<td><?php echo $filas_resultado_venta['NomArt']?></td>
			<td><?php echo $filas_resultado_venta['precio']?><?php echo $sesion->moneda;?> </td>
		<td>
					<?php $elIVA=number_format((($filas_resultado_venta['precio'])*($filas_resultado_venta['IVA'])/100),2);?>
					<?php echo $elIVA?><?php echo $sesion->moneda;?></td>  
			<td><?php echo $filas_resultado_venta['cantidad']?> </td>
			<td><?php echo number_format((($filas_resultado_venta['precio']+$elIVA)*($filas_resultado_venta['cantidad'])),2)?><?php echo $sesion->moneda;?></td>
		</tr>				
				<?php  $total=$total+($filas_resultado_venta['precio']+$elIVA)*($filas_resultado_venta['cantidad']);?>
				<br />				
				<?php } ?>
		</table>
				<br />
				<hr />
				<div class="der">
				Total a abonar: <b><?php echo number_format($total,2)?><?php echo $sesion->moneda;?></b>
				<br />			
			
				Forma de pago:   <?php echo $filas_resultado_venta_H['ForPag']?><br />
				Entregado:   <?php echo number_format($filas_resultado_venta_H['entregado'],2)?><?php echo $sesion->moneda;?><br />
				A devolver: <?php echo number_format(($filas_resultado_venta_H['entregado']-$total),2)?><?php echo $sesion->moneda;?>
				</div>
	<br />
<?php echo $filas_resultado_empresa['ticket_grande']?>
<p class="chiquito">
<?php echo $filas_resultado_empresa['ticket_chiquito']?>
</p>
</div>
</body>
</html>
