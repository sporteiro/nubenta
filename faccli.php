<?php
declare(strict_types=1);
require_once('clases/cargador.php');
$sesion = new sesion();
$conectarBD = new conexion();
$conn = $conectarBD->getConexion();

if (!$sesion->conectado) {
	header("location: index.php");
}

//CONSULTO LOS ARTICULOS QUE ESTAN SIENDO ELEGIDOS PARA VENDERSE
$stmt = $conn->prepare("SELECT * FROM ventas WHERE CodUsu = ?");
$stmt->bind_param('s', $sesion->CodUsu);
$stmt->execute();
$resultado_venta = $stmt->get_result();


//INSERTAR EN LA TABLA VENTAS LA COMPRA PARCIAL, DESPUES SE BORRARA
if (isset($_POST["insertaryborrar"]))  {


	$fecha = $conn->real_escape_string($_POST["ano"] . "-" . $_POST["mes"] . "-" . $_POST["dia"]);

	
	$hora = $conn->real_escape_string($_POST["hora"]);
	$ForPag = $conn->real_escape_string($_POST["ForPag"]);
	$entregado = $conn->real_escape_string($_POST["entregado"]);

	$stmt2 = $conn->prepare("UPDATE ventas set fecha = ?, hora = ?, ForPag = ?, entregado = ? WHERE CodUsu = ?");
	$stmt2->bind_param('sssss', $fecha, $hora, $ForPag, $entregado, $sesion->CodUsu);
	$stmt2->execute();
	$stmt2->close();


	
	$stmt3 = $conn->prepare("INSERT INTO faccli (OrdMov,CodFac,CodArt,NomArt,DesArt,precio,IVA,cantidad,stock,fecha,hora,ForPag,entregado,CodUsu) SELECT OrdMov,CodFac,CodArt,NomArt,DesArt,precio,IVA,cantidad,stock,fecha,hora,ForPag,entregado,CodUsu FROM ventas WHERE CodUsu = ?");
	$stmt3->bind_param('s', $sesion->CodUsu);
	$stmt3->execute();
	$stmt3->close();


	
	$stmt4 = $conn->prepare("UPDATE articulos as A join ventas as V ON A.CodArt=V.CodArt set A.cantidad=A.cantidad-V.cantidad, A.OrdMov=A.OrdMov+1 WHERE A.CodArt=V.CodArt AND A.CodUsu = ?");
	$stmt4->bind_param('s', $sesion->CodUsu);
	$stmt4->execute();
	$stmt4->close();
	
		
	
	$stmt5 = $conn->prepare("DELETE FROM ventas WHERE CodUsu = ?");
	$stmt5->bind_param('s', $sesion->CodUsu);
	$stmt5->execute();
	$stmt5->close();

                        if (isset($_REQUEST['regalo'])) {
                                /*ASI ERA
                                        header("Location: imprimirconregalo.php" );
                                */
                                header("Location: imprimirconregalo.php?otrocodigo=".$_POST['codfac']."");      
                        }
                        else {
                                /* ASI ERA ANTES (se suprime imprimir.php)
                                   //header("Location: imprimir.php" );
                                */
                                header("Location: imprimirotra.php?otrocodigo=".$_POST['codfac']."");
                        }
}
?>
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="estilo.css" />
<script type="text/javascript">

function cambio() {
	var ent=document.getElementById('entregado').value;
	var tot=document.getElementById('total').value;
	var cam=ent-tot
	document.getElementById('vuelto').value=cam.toFixed(2);


}
</script>
<script type="text/javascript">

function comprobar_factura()	{
	var form_factura="";
		
	if (document.getElementById("entregado").value=="") {
		form_factura+="Falta el dinero entregado\n";
		document.getElementById("entregado").className='botonesrojos';
	}
	if (document.getElementById("entregado").value!="") {
		var vuelto=document.getElementById('vuelto').value;
		if (vuelto<0)	{	
			form_factura+="El dinero entregado es menor que el total\n";
			document.getElementById("entregado").className='botonesrojos';		
		}
		
	}
	if (isNaN(document.getElementById("entregado").value)==true) {
		form_factura+="El dinero entregado debe ser un numero\n";
		document.getElementById("entregado").className='botonesrojos';
							
	}
	if (form_factura!="") {
			alert("Comprobar los siguientes errores: \n\n"+form_factura);
		}
	 
	else	{
			document.getElementById("form_vender").submit();
		}
}

</script>
</head>
<body style="background-color: black;">
<div class="iframes">
<form action="faccli.php" method="post" name="form_vender" id="form_vender">
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
					while ($filas_resultado_venta= $resultado_venta->fetch_assoc()) { ?>
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
					
				<?php  $total=$total+($filas_resultado_venta['precio']+$elIVA)*($filas_resultado_venta['cantidad']);  
				$codfac=$filas_resultado_venta['CodFac'];				
				} ?>	

				<hr />
				<tr class="negro">
					<td colspan="5"></td><td>Total a abonar:</td> <td><b><?php echo number_format($total,2)?><?php echo $sesion->moneda;?></b></td>
					<input type="hidden" id="total" name="total" value="<?php echo $total?>"/>
				</tr>
			</table>
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
			
			Forma de pago:  <select name="ForPag">
						<option value="efectivo">En efectivo</option>
						<option value="tarjeta">Con tarjeta</option>
					</select>				
			<input type="hidden" name="codfac" value="<?php echo $codfac;?>"/>
			<input type="hidden" name="insertaryborrar"/>
			<p>
			Entregado: <input type="text" size="6" maxlength="6" name="entregado" id="entregado" onChange="cambio()"/>
			A devolver: <input type="text" size="6" maxlength="6" name="vuelto" id="vuelto" readonly="readonly" />
            Imprimir ticket de regalo <input name="regalo" type="checkbox" id="regalo"/>
			</p>
			<p style="text-align: center;">
            	
				<input type="button" value="Efectuar Pago" class="botones" onClick="comprobar_factura()"/>
			</p>
			</form>
</div>
</body>
</html>
