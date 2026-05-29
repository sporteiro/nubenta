<?php
declare(strict_types=1);
require_once('clases/cargador.php');
$sesion = new sesion();
$conectarBD = new conexion();
$conn = $conectarBD->getConexion();

if (!$sesion->conectado) {
	header("location: index.php");
}

//CONSULTO LOS ARTICULOS QUE ESTAN SIENDO ELEGIDOS PARA COMPRARSE
$stmt = $conn->prepare("SELECT * FROM compras WHERE CodUsu = ?");
$stmt->bind_param('s', $sesion->CodUsu);
$stmt->execute();
$resultado_venta = $stmt->get_result();


//INSERTAR EN LA TABLA VENTAS LA COMPRA PARCIAL, DESPUES SE BORRARA
if ( (isset($_POST["insertaryborrar"])) )  {

	$fecha = $conn->real_escape_string($_POST["ano"] . "-" . $_POST["mes"] . "-" . $_POST["dia"]);
	$hora = $conn->real_escape_string($_POST["hora"]);
	$ForPag = $conn->real_escape_string($_POST["ForPag"]);
	$NumeroFactura = $conn->real_escape_string($_POST["NumeroFactura"]);
	$AlbaranFactura = $conn->real_escape_string($_POST["AlbaranFactura"]);

	$stmt2 = $conn->prepare("UPDATE compras set fecha = ?, hora = ?, ForPag = ?, NumeroFactura = ?, albaran_factura = ? WHERE CodUsu = ?");
	$stmt2->bind_param('ssssss', $fecha, $hora, $ForPag, $NumeroFactura, $AlbaranFactura, $sesion->CodUsu);
	$stmt2->execute();
	$stmt2->close();


	
	$stmt3 = $conn->prepare("INSERT INTO facpro (NumeroFactura,OrdMov,CodFac,CodArt,NomArt,DesArt,precio,PreCom,descuento,IVA,recargo,cantidad,stock,fecha,hora,ForPag,albaran_factura,CodUsu) SELECT NumeroFactura,OrdMov,CodFac,CodArt,NomArt,DesArt,precio,PreCom,descuento,IVA,recargo,cantidad,stock,fecha,hora,ForPag,albaran_factura,CodUsu FROM compras WHERE CodUsu = ?");
	$stmt3->bind_param('s', $sesion->CodUsu);
	$stmt3->execute();
	$stmt3->close();


	
	$stmt4 = $conn->prepare("UPDATE articulos as A join compras as C ON A.CodArt=C.CodArt set A.cantidad=A.cantidad+C.cantidad, A.OrdMov=A.OrdMov+1,A.precio=C.precio,A.PreCom=C.PreCom WHERE A.CodArt=C.CodArt");
	$stmt4->execute();
	$stmt4->close();

	$stmt5 = $conn->prepare("DELETE FROM compras WHERE CodUsu = ?");
	$stmt5->bind_param('s', $sesion->CodUsu);
	$stmt5->execute();
	$stmt5->close();
	/* ASI ERA ANTES (se suprime imprimircompra.php)
	header("Location: imprimircompra.php" );
	*/ 
	header("Location: imprimircompraotra.php?otrocodigo=".$_POST['codfac']."");
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Kamalakshi ::: Usado por <?php echo $_SESSION['nombreusuario'];?></title>
<link rel="stylesheet" type="text/css" href="estilo.css" />
<script type="text/javascript">

function comprobar_factura()	{
	var form_factura="";
		
	if (document.getElementById("NumeroFactura").value=="") {
		form_factura+="Falta el Numero de Albaran o Factura\n";
		document.getElementById("NumeroFactura").className='botonesrojos';
	}
	if (form_factura!="") {
			alert("Comprobar los siguientes errores: \n\n"+form_factura);
		}
	 
	else	{
			document.getElementById("form_comprar").submit();
		}
}

</script>
</head>
<body style="background-color: black;">
<div class="iframes">
<form action="facpro.php" method="post" name="form_comprar" id="form_comprar">
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
					$total_cantidad=0;
					$total_bruto=0;
					$total_descuento=0;
					$total_IVA=0;
					$total_recargo=0;
					
					while ($filas_resultado_venta= $resultado_venta->fetch_assoc()) { ?>
				<tr>
					<td><?php echo $mas++ ?></td> 
					<td><?php echo $filas_resultado_venta['CodArt']?></td> 
					<td><?php echo $filas_resultado_venta['NomArt']?></td> 
					<td><?php echo $filas_resultado_venta['PreCom']?><?php echo $sesion->moneda;?></td>
					<td>
					<?php $eldescuento=($filas_resultado_venta['PreCom'])*($filas_resultado_venta['descuento'])/100;
						$eldescuentocu=$eldescuento*$filas_resultado_venta['cantidad'];
						echo number_format($eldescuento,2)?><?php echo $sesion->moneda;?></td>  
					<td>
					<?php $elIVA=($filas_resultado_venta['PreCom'])*($filas_resultado_venta['IVA'])/100;
						$elIVAcu=$elIVA*$filas_resultado_venta['cantidad'];
						echo number_format($elIVA,2)?><?php echo $sesion->moneda;?></td>
                    <td>
					<?php $elrecargo=($filas_resultado_venta['PreCom'])*($filas_resultado_venta['recargo'])/100;
						$elrecargocu=$elrecargo*$filas_resultado_venta['cantidad'];
						echo number_format($elrecargo,2)?><?php echo $sesion->moneda;?></td>  
					<td><?php echo $filas_resultado_venta['cantidad']?></td> 
                    
					<td>
					<?php echo number_format(($filas_resultado_venta['PreCom']+$elIVA+$elrecargo-($eldescuento))*($filas_resultado_venta['cantidad']),2)?><?php echo $sesion->moneda;?>
                    </td>
				</tr>
				<?php 
				  $total_cantidad=$total_cantidad+$filas_resultado_venta['cantidad'];
				  $total_bruto=$total_bruto+$filas_resultado_venta['PreCom']*$filas_resultado_venta['cantidad'];
				  $total_descuento=$total_descuento+$eldescuentocu;
			      $total_IVA=$total_IVA+$elIVAcu;
				  $total_recargo=$total_recargo+$elrecargocu;
				  $total=$total+($filas_resultado_venta['PreCom']+$elIVA+$elrecargo-($eldescuento))*($filas_resultado_venta['cantidad']); 
				$codfac=$filas_resultado_venta['CodFac'];	
	 } ?>

				<tr class="negro">						
					<td colspan="3">Totales:</td>
                    <td><?php echo number_format($total_bruto, 2)?><?php echo $sesion->moneda;?></td>
                    <td><?php echo number_format($total_descuento, 2)?><?php echo $sesion->moneda;?></td>
                    <td><?php echo number_format($total_IVA, 2)?><?php echo $sesion->moneda;?></td>
					<td><?php echo number_format($total_recargo, 2)?><?php echo $sesion->moneda;?></td> 
					<td><?php echo $total_cantidad?></td>
                    <td></td>
				</tr>
                <tr class="negro">
                	<td colspan="6"></td>
                	<td colspan="2">Total a abonar:</td> 
					<td><b><?php echo number_format($total, 2)?><?php echo $sesion->moneda;?></b></td>
                </tr>
			</table>
			<p>
			Numero de Factura: <input type="text" name="NumeroFactura" id="NumeroFactura" />
			Albaran (remito) o Factura: <select name="AlbaranFactura">
						<option value="albaran">Albaran</option>
						<option value="factura">Factura</option>
					   </select>
			</p>
			<p>			
			Dia:
			<select name="dia">
			<option selected="selected" value="<?php echo date('j');?>">
			<?php echo date('j');?>
			</option>
			<?php 
			for ($d=1;$d<32;$d++) { ?>
			<option><?php echo $d?></option>
			<?php } ?>
			</select>
			Mes:
			<select name="mes">
			<?php 
			$nombremes= array('--','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
			for ($m=1;$m<13;$m++) { ?>
			<option value="<?php echo $m?>"><?php echo $nombremes[$m]?></option>
			<?php } ?>
			<option selected="selected" value="<?php echo date('n');?>">
			<?php echo $nombremes[date('n')];?>
			</option>
			</select>
			A&ntilde;o:
			<select name="ano">
			<option selected="selected" value="<?php echo date('Y');?>">
			<?php echo date('Y');?>
			</option>
			<?php 
			for ($a=2011;$a<2051;$a++) { ?>
			<option><?php echo $a?></option>
			<?php } ?>
			</select>
			
			Hora: <input type="text" value="<?php echo date('H:i'); ?>" name="hora" size="5" maxlength="5"/>
			
			</p>
			Forma de pago:  <select name="ForPag">
						<option value="efectivo">En efectivo</option>
						<option value="tarjeta">Con tarjeta</option>
					</select>				
			<input type="hidden" name="codfac" value="<?php echo $codfac;?>"/>
			<input type="hidden" name="insertaryborrar"/>
			<p style="text-align: center;">
				<input type="button" value="Efectuar Pago" class="botones" onClick="comprobar_factura()"/>
			</p>
			</form>
</div>
</body>
</html>
