<?php
declare(strict_types=1);
require_once('clases/cargador.php');
$sesion = new sesion();
$conectarBD = new conexion();
$conn = $conectarBD->getConexion();

if (!$sesion->conectado) {
	header("location: index.php");
}

//ENTRA --- SALE
$consulta_union2 = "(SELECT a.CodFam, x.nombre, f.CodArt, f.CodFac, f.OrdMov, f.NomArt, f.cantidad as 'entra', f.cantidad=0 as 'sale', f.stock, f.fecha, f.NumeroFactura,f.PreCom,f.albaran_factura FROM facpro f JOIN articulos a on f.CodArt=a.CodArt JOIN familias x on a.CodFam=x.CodFam WHERE a.CodUsu = ?)
 UNION
(select aa.CodFam, xx.nombre, ff.CodArt, ff.CodFac, ff.OrdMov, ff.NomArt, ff.cantidad=99999,ff.cantidad, ff.stock,ff.fecha, ff.CodFac,ff.precio, ff.ForPag  from faccli ff JOIN articulos aa on ff.CodArt=aa.CodArt JOIN familias xx on aa.CodFam=xx.CodFam WHERE aa.CodUsu = ?)  order by fecha DESC,CodFam , OrdMov,CodFac";
$stmt = $conn->prepare($consulta_union2);
$stmt->bind_param('ss', $sesion->CodUsu, $sesion->CodUsu);
$stmt->execute();
$resultado_union2 = $stmt->get_result();

//CAJA
$stmt2 = $conn->prepare("SELECT * FROM faccli WHERE CodUsu = ? ORDER BY fecha DESC, CodFac");
$stmt2->bind_param('s', $sesion->CodUsu);
$stmt2->execute();
$resultado_caja = $stmt2->get_result();
?>

<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Kamalakshi ::: Usado por <?php echo $_SESSION['nombreusuario'];?></title>
<link rel="stylesheet" type="text/css" href="estilo.css" />
<script type="text/javascript" src="js/articulo.js"></script>
</head>
<body style="background-color: black;">
<div class="iframes">
	<!-- caja -->
    <br />
    <br />
    <div>
    <a href="#" onClick="mostrar_actualizar_familia()" id="boton_actualizar_familia">Caja diaria (solo ventas)</a>
	  <div class="invisible" id="actualizar_familia">
	    <div>
		<span style="float: right;"><form><input  class="botonesrojos" type="button" value="X" onClick="ocultar_actualizar_familia()" /></form></span>
		<b>Caja diaria (solo ventas)</b><br />
        <?php   $repetidoC=-1; 
 while ($filas_caja= $resultado_caja->fetch_assoc()) {
         if ($repetidoC!=$filas_caja['fecha']) {  ?>
   		<?php if ($repetidoC!=-1) echo "<tr class='negro'><td colspan='6'></td><td>Total del dia (sin IVA)</td><td>".$todo_totalC."<?php echo $sesion->moneda;?></td><td>Total+IVA <b>".number_format($total_mas_IVA,2)."<?php echo $sesion->moneda;?></b></td></tr> </table><br />" ?>
		<table>
		<tr class="negro">
		<td colspan="4"><b>Fecha: <?php $FC=$filas_caja['fecha'];
			 echo $FC[8].$FC[9].'/'.$FC[5].$FC[6].'/'.$FC[0].$FC[1].$FC[2].$FC[3]?></td> </b> 
		</td>
		<td colspan="5"></td>
		</tr>
		<?php $masC=1;?>
		<tr class="negro">
		<td>Orden</td>
		<td>Producto</td>
		<td>Sale</td>
		<td>Numero Ticket</td>
		<td>Stock</td>
		<td>Precio</td>
        <td>IVA</td>
		<td>Total Articulo</td>
        <td>Total+IVA</td>
		</tr>
		<?php $todo_totalC=0;
				$total_mas_IVA=0;
		?>
		<?php $repetidoC=$filas_caja['fecha']; } ?>
		<tr>
		<td><?php echo $masC++?></td>
		<td><?php echo $filas_caja['NomArt'];?></td>
		<td><?php echo $filas_caja['cantidad'];?></td>	
		<td><?php echo $filas_caja['CodFac'];?></td>	
		<td><?php echo $filas_caja['stock'];?></td>	
		<td><?php echo $filas_caja['precio'];?><?php echo $sesion->moneda;?></td>	
       	<td>
				
			
			<?php  $elIVA=number_format((($filas_caja['precio'])*($filas_caja['IVA'])/100),2);
					$elIVAcu=$elIVA*$filas_caja['cantidad'];
		 			echo $elIVA?><?php echo $sesion->moneda;?></td>
		<td><?php 
			$total_AC=($filas_caja['precio']* $filas_caja['cantidad']);
			echo $total_AC;?><?php echo $sesion->moneda;?>
        </td>
        <td><?php echo number_format($total_AC+$elIVAcu,2);?><?php echo $sesion->moneda;?></td>
			<?php 
			$todo_totalC=$todo_totalC+$total_AC;
			$total_mas_IVA=$total_mas_IVA+$total_AC+$elIVAcu;
			?>
		</tr>

<?php } ?><?php if ($repetidoC!=-1) echo "<tr class='negro'><td colspan='6'></td><td>Total del dia (sin IVA)</td><td>".$todo_totalC."<?php echo $sesion->moneda;?></td><td>Total+IVA <b>".number_format($total_mas_IVA,2)."<?php echo $sesion->moneda;?></b></td></tr> </table><br />" ?>
         </div>
	</div>
    
    	<br />
    <!-- compra venta -->
	<br />
	<a href="#" onClick="mostrar_nuevo_familia()" id="boton_nuevo_familia">Listado compra/venta</a>
	<div class="invisible" id="nuevo_familia">
		<div>
		<span style="float: right;"><form><input  class="botonesrojos" type="button" value="X" onClick="ocultar_nuevo_familia()" /></form></span>		
        <b>Listado compra/venta</b><br />
<?php   $repetido3=-1; 
 while ($filas_union2= $resultado_union2->fetch_assoc()) {
         if ($repetido3!=$filas_union2['fecha']) {  ?>
   		<?php if ($repetido3!=-1) echo "<tr class='negro'><td colspan='5'>Total del dia con IVA y recargo aproximado (18% y 4%)</td><td>".number_format($total_mas_iva_cp,2)."<?php echo $sesion->moneda;?></td><td>Total del dia (sin IVA)</td><td><b>".number_format($todo_total,2)."<?php echo $sesion->moneda;?></b></td></tr> </table><br />" ?>
		<table>
		<tr class="negro">
		<td colspan="4"><b>Fecha: <?php $F=$filas_union2['fecha'];
			 echo $F[8].$F[9].'/'.$F[5].$F[6].'/'.$F[0].$F[1].$F[2].$F[3]?></td> </b> 
		</td>
		<td colspan="4"></td>
		</tr>
		<?php $mas=1;?>
		<tr class="negro">
		<td>Orden</td>
		<td>Producto</td>
		<td>Entra</td>
		<td>Sale</td>
		<td>Numero Ticket/Factura</td>
		<td>Stock</td>
		<td>Precio</td>
		<td>Total Articulo</td>
		</tr>
		<?php $todo_total=0;
		$total_mas_iva_cp=0;
		?>
		<?php $repetido3=$filas_union2['fecha']; } ?>
		<tr>
		<td><?php echo $mas++?></td>
		<td><?php echo $filas_union2['NomArt'];?></td>
		<td><?php echo $filas_union2['entra'];?></td>	
		<td><?php echo $filas_union2['sale'];?></td>	
		<td><?php echo $filas_union2['NumeroFactura'];?> <i>(<?php echo $filas_union2['albaran_factura'];?>)</i></td>	
		<td><?php echo $filas_union2['stock'];?></td>	
		<td><?php echo $filas_union2['PreCom'];?></td>	
		<td><?php 
			$total_A=($filas_union2['PreCom']* $filas_union2['sale'])-($filas_union2['PreCom']* $filas_union2['entra']);
			echo $total_A;?>
        
        </td>
            
			<?php $todo_total=$todo_total+$total_A;
				  $total_mas_iva_cp=$total_mas_iva_cp+(($filas_union2['PreCom']*1.18)*($filas_union2['sale']))-(($filas_union2['PreCom']*1.22)* ($filas_union2['entra']))?>
		</tr>

<?php } ?><?php if ($repetido3!=-1) echo  "<tr class='negro'><td colspan='5'>Total del dia con IVA y recargo aproximado (18% y 4%)</td><td>".number_format($total_mas_iva_cp,2)."<?php echo $sesion->moneda;?></td><td>Total del dia (sin IVA)</td><td><b>".number_format($todo_total,2)."<?php echo $sesion->moneda;?></b></td></tr> </table><br />" ?>
		</div>
	</div>
</div>
</body>
</html>
