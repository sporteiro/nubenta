<?php
declare(strict_types=1);
require_once('clases/cargador.php');
$sesion = new sesion();
$conectarBD = new conexion();
$conn = $conectarBD->getConexion();

if (!$sesion->conectado) {
	header("location: index.php");
}

//CONSULTA DE FAMILIAS
$stmt = $conn->prepare("SELECT * FROM familias WHERE CodUsu = ?");
$stmt->bind_param('s', $sesion->CodUsu);
$stmt->execute();
$resultado_familias = $stmt->get_result();

//CONSULTA DE PROVEEDORES
$consulta_proveedores = "SELECT P.*,C.NumCue,B.nombre FROM (proveedores P LEFT JOIN cuentas C on P.CodPro=C.CodPro) LEFT JOIN bancos B on B.CodBan=C.CodBan WHERE P.CodUsu = ?";
$stmt2 = $conn->prepare($consulta_proveedores);
$stmt2->bind_param('s', $sesion->CodUsu);
$stmt2->execute();
$resultado_proveedores = $stmt2->get_result();

//CONSULTA DE ARTICULOS
$consulta_articulos = "SELECT A.*, F.nombre, P.NomPro FROM articulos A JOIN familias F JOIN proveedores P ON A.CodFam=F.CodFam AND A.CodPro=P.CodPro WHERE A.CodUsu = ? ORDER BY A.CodArt";
$stmt3 = $conn->prepare($consulta_articulos);
$stmt3->bind_param('s', $sesion->CodUsu);
$stmt3->execute();
$resultado_articulos = $stmt3->get_result();

//Consultar Facturas clientes
$stmt4 = $conn->prepare("SELECT * FROM faccli WHERE CodUsu = ?");
$stmt4->bind_param('s', $sesion->CodUsu);
$stmt4->execute();
$resultado_faccli = $stmt4->get_result();

//Consultar Facturas proveedores
$consulta_facpro = "SELECT facpro.*,UPPER(proveedores.NomPro) as NomPro FROM facpro JOIN articulos JOIN proveedores on facpro.CodArt=articulos.CodArt AND articulos.CodPro=proveedores.CodPro WHERE facpro.CodUsu = ?";
$stmt5 = $conn->prepare($consulta_facpro);
$stmt5->bind_param('s', $sesion->CodUsu);
$stmt5->execute();
$resultado_facpro = $stmt5->get_result();


//BUSCAR ARTICULOS
if (isset($_GET['buscar']))  {
	$articulo = $conn->real_escape_string($_GET['buscar']);
}
else {
	$articulo = 'Buscar...';
}

$articulo_param = "%$articulo%";
$stmt6 = $conn->prepare("SELECT A.*, F.nombre, P.NomPro FROM articulos A JOIN familias F JOIN proveedores P ON A.CodFam=F.CodFam AND A.CodPro=P.CodPro WHERE (A.NomArt LIKE ? OR A.CodArt LIKE ?) AND A.CodUsu = ?");
$stmt6->bind_param('sss', $articulo_param, $articulo_param, $sesion->CodUsu);
$stmt6->execute();
$resultado_articulos_busca = $stmt6->get_result();
$total_filas_resultado_articulos_busca = $resultado_articulos_busca->num_rows;


//BUSCAR FAMILIAS
if (isset($_GET['buscar_familia']))  {
	$familia = $conn->real_escape_string($_GET['buscar_familia']);
}
else {
	$familia = 'Buscar...';
}

$familia_param = "%$familia%";
$stmt7 = $conn->prepare("SELECT * FROM familias WHERE (nombre LIKE ?) AND CodUsu = ?");
$stmt7->bind_param('ss', $familia_param, $sesion->CodUsu);
$stmt7->execute();
$resultado_familias_busca = $stmt7->get_result();
$total_filas_resultado_familias_busca = $resultado_familias_busca->num_rows;

//BUSCAR PROVEEDORES
if (isset($_GET['buscar_proveedor']))  {
	$proveedor = $conn->real_escape_string($_GET['buscar_proveedor']);
}
else {
	$proveedor = 'Buscar...';
}

$proveedor_param = "%$proveedor%";
$consulta_proveedores_busca = "SELECT P.*,C.NumCue,B.nombre FROM (proveedores P LEFT JOIN cuentas C on P.CodPro=C.CodPro) LEFT JOIN bancos B on B.CodBan=C.CodBan WHERE (NIF_CIF LIKE ? OR NomPro LIKE ?) AND P.CodUsu = ?";
$stmt8 = $conn->prepare($consulta_proveedores_busca);
$stmt8->bind_param('sss', $proveedor_param, $proveedor_param, $sesion->CodUsu);
$stmt8->execute();
$resultado_proveedores_busca = $stmt8->get_result();
$total_filas_resultado_proveedores_busca = $resultado_proveedores_busca->num_rows;

//BUSCAR FACTURAS CLIENTES
if (isset($_GET['buscar_faccli']))  {
	$faccli = $conn->real_escape_string($_GET['buscar_faccli']);
}
else {
	$faccli = 'Buscar...';
}

$faccli_param = "%$faccli%";
$stmt9 = $conn->prepare("SELECT * FROM faccli WHERE (CodFac LIKE ? OR fecha LIKE ?) AND CodUsu = ?");
$stmt9->bind_param('sss', $faccli_param, $faccli_param, $sesion->CodUsu);
$stmt9->execute();
$resultado_faccli_busca = $stmt9->get_result();
$total_filas_resultado_faccli_busca = $resultado_faccli_busca->num_rows;


//BUSCAR FACTURAS PROVEEDORES
if (isset($_GET['buscar_facpro']))  {
	$facpro = $conn->real_escape_string($_GET['buscar_facpro']);
}
else {
	$facpro = 'Buscar...';
}

$facpro_param = "%$facpro%";
$consulta_facpro_busca = "SELECT facpro.*,UPPER(proveedores.NomPro) as NomPro FROM facpro JOIN articulos JOIN proveedores on facpro.CodArt=articulos.CodArt AND articulos.CodPro=proveedores.CodPro WHERE (NumeroFactura LIKE ? OR fecha LIKE ?) AND facpro.CodUsu = ?";
$stmt10 = $conn->prepare($consulta_facpro_busca);
$stmt10->bind_param('sss', $facpro_param, $facpro_param, $sesion->CodUsu);
$stmt10->execute();
$resultado_facpro_busca = $stmt10->get_result();
$total_filas_resultado_facpro_busca = $resultado_facpro_busca->num_rows;
?>

<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Kamalakshi ::: Usado por <?php echo $_SESSION['nombreusuario'];?></title>
<link rel="stylesheet" type="text/css" href="estilo.css" />
<script type="text/javascript" src="js/js.js"></script>
<script type="text/javascript" src="js/articulo.js"></script>
<script type="text/javascript" src="js/proveedor.js"></script>
</head>
<body style="background-color: black;">
<div class="iframes">
<!-- Articulos -->	
		<!-- INICIO VER ARTICULOS -->
		<p><b>Consultar Articulos</b></p>
		<a href="#" onClick="mostrar_actualizar_articulo()" id="boton_actualizar_articulo">Todos los articulos:<img src="img/actualizar.png" width="20px" height="20px" style="vertical-align: middle;"/></a>

	  <div class="invisible" id="actualizar_articulo">
	    <div class="agregados">
		<span style="float: right;"><input  class="botonesrojos" type="button" value="X" onClick="ocultar_actualizar_articulo()" /></span>
		<b>Todos los articulos: </b>
		<table>
		   <tr>
			<td>Codigo</td>
			<td>Nombre</td>
			<td>Familia</td>
			<td>Proveedor</td>
			<td>Descripcion</td>
			<td>Precio</td>
			<td>P/compra</td>
			<td>Stock</td>	
		   </tr>
		<?php while($filas_resultado_articulos= $resultado_articulos->fetch_assoc()) { ?>
		   <tr class="fondonegro">
			
			<td>
			<?php echo $filas_resultado_articulos['CodArt']?>
			</td>

			<td>	
			<?php echo $filas_resultado_articulos['NomArt']?>
			</td>	

			<td>			
			<?php echo $filas_resultado_articulos['CodFam']?>  (<?php echo $filas_resultado_articulos['nombre']?>)
						
			</td>			
			
			<td>
			<?php echo $filas_resultado_articulos['CodPro']?> (<?php echo $filas_resultado_articulos['NomPro']?>)
			</td>
					
			
			<td>					
			<?php echo $filas_resultado_articulos['DesArt']?>
			</td>
			<td>
			<?php echo $filas_resultado_articulos['precio']?><?php echo $sesion->moneda;?>
			</td>
			<td>		
			<?php echo $filas_resultado_articulos['PreCom']?><?php echo $sesion->moneda;?>
			
			</td>
			<td>
			<?php echo $filas_resultado_articulos['cantidad']?>
			</td>
		
		   </tr>
		  
		<?php } ?>
		</table>
	     
          </div>
	</div>
	<!-- FIN CONSULTAR ARTICULO -->

	<!-- INICIO BUSCAR ARTICULO -->
	
	<br /><br />
	
	  <div class="visible" id="eliminar_articulo">
	    <div class="agregados">
		<b>Buscar Articulo: </b>
		<form action="consulta.php?buscar=" method="get">
		<input type="text" onChange="this.form.submit()" name="buscar" id="buscar" value="<?php echo $articulo;?>" onBlur="if(this.value=='') this.value='Buscar...';" onFocus="if(this.value=='Buscar...') this.value='';"/>
		<a href="#" onClick="this.form.submit()">Buscar <img src="img/buscar.png" width="20px" height="20px" style="vertical-align: middle;"/></a>
		</form>
		<?php if (isset($_GET['buscar'])) { ?>
			<?php if ($total_filas_resultado_articulos_busca==0) { ?>
				No se encontro ningun articulo.
			<?php } else { ?> 
            	

		<div>	
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
			<?php while ($filas_resultado_articulos_busca= $resultado_articulos_busca->fetch_assoc()) { ?>
			   <tr class="fondonegro">
				<td><?php echo $filas_resultado_articulos_busca['CodArt'];?></td>
				<td><?php echo $filas_resultado_articulos_busca['CodFam'];?></td>
				<td><?php echo $filas_resultado_articulos_busca['CodPro'];?></td>
				<td><?php echo $filas_resultado_articulos_busca['NomArt'];?></td>
				<td><?php echo $filas_resultado_articulos_busca['DesArt'];?></td>
				<td><?php echo $filas_resultado_articulos_busca['precio'];?></td>
				<td><?php echo $filas_resultado_articulos_busca['PreCom'];?></td>
				<td><?php echo $filas_resultado_articulos_busca['cantidad'];?></td>
					
			   </tr>
			
	
			<?php } ?>
			</table>
		</div>
		<?php } } ?>
		
	  </div>
	</div>
	<!-- FIN BORRAR ARTICULO -->
<!-- FIN ARTICULO -->	
	<hr />
<!-- FAMILIAS-->
		<p><b>Consultar familias</b></p>
	<a href="#" onClick="mostrar_actualizar_familia()" id="boton_actualizar_familia">Todas las familias:<img src="img/actualizar.png" width="20px" height="20px" style="vertical-align: middle;"/></a>

	  <div class="invisible" id="actualizar_familia">
	    <div class="agregados">
		<span style="float: right;"><input  class="botonesrojos" type="button" value="X" onClick="ocultar_actualizar_familia()" /></span>
		<b>Todas las familias: </b>
		<table>
		   <tr>
			<td>Codigo</td>
			<td>Nombre</td>
		   </tr>
		<?php while($filas_resultado_familias= $resultado_familias->fetch_assoc()) { ?>
		   <tr class="fondonegro">
			
			<td>
			<?php echo $filas_resultado_familias['CodFam']?>
			</td>

			<td>	
			<?php echo $filas_resultado_familias['nombre']?>
			</td>	
	
		   </tr>
		  
		<?php } ?>
		</table>
	     
          </div>
	</div>
	<!-- FIN CONSULTAR familia -->

	<!-- INICIO BUSCAR familia -->
	
	<br /><br />
	
	  <div class="visible">
	    <div class="agregados">
		<b>Buscar Familia: </b>
		<form action="consulta.php?buscar_familia=" method="get">
		<input type="text" onChange="this.form.submit()" name="buscar_familia" value="<?php echo $familia?>" onBlur="if(this.value=='') this.value='Buscar...';" onFocus="if(this.value=='Buscar...') this.value='';"/>
		<a href="#" onClick="this.form.submit()">Buscar <img src="img/buscar.png" width="20px" height="20px" style="vertical-align: middle;"/></a>
		</form>
		<?php if (isset($_GET['buscar_familia'])) { ?>
			<?php if ($total_filas_resultado_familias_busca==0) { ?>
				No se encontro ninguna familia .
			<?php } else { ?>

		<div>	
			<table>
			   <tr>
				<td>Codigo</td>
				<td>Nombre</td>
				
			   </tr>
			<?php while ($filas_resultado_familias_busca= $resultado_familias_busca->fetch_assoc()) { ?>
			   <tr class="fondonegro">
				<td><?php echo $filas_resultado_familias_busca['CodFam'];?></td>
				<td><?php echo $filas_resultado_familias_busca['nombre'];?></td>
			   </tr>
			
	
			<?php } ?>
			</table>
		</div>
		<?php } } ?>
		
	  </div>
	</div>
	<!-- FIN BORRAR familias -->
<!-- FIN familias -->	
<hr />
<!-- proveedores-->
		<p><b>Consultar proveedores</b></p>
	<a href="#" onClick="mostrar_actualizar_proveedor()" id="boton_actualizar_proveedor">Todos los proveedores:<img src="img/actualizar.png" width="20px" height="20px" style="vertical-align: middle;"/></a>

	  <div class="invisible" id="actualizar_proveedor">
	    <div class="agregados">
		<span style="float: right;"><input  class="botonesrojos" type="button" value="X" onClick="ocultar_actualizar_proveedor()" /></span>
		<b>Todos los proveedores: </b>
		<table>
		   <tr>
			<td>Codigo</td>
			<td>Nombre</td>
			<td>NIF/CIF</td>
			<td>Numero de cuenta</td>
			
		   </tr>
		<?php while($filas_resultado_proveedores= $resultado_proveedores->fetch_assoc()) { ?>
		   <tr class="fondonegro">
			
			<td>
			<?php echo $filas_resultado_proveedores['CodPro']?>
			</td>

			<td>	
			<?php echo $filas_resultado_proveedores['NomPro']?>
			</td>	

			<td>	
			<?php echo $filas_resultado_proveedores['NIF_CIF']?>
			</td>	
			<td>	
			(<?php echo $filas_resultado_proveedores['nombre']?>) <?php echo $filas_resultado_proveedores['NumCue']?>
			</td>	
			
		   </tr>
		  
		<?php } ?>
		</table>
	     
          </div>
	</div>
	<!-- FIN CONSULTAR proveedores -->

	<!-- INICIO BUSCAR proveedores -->
	
	<br /><br />
	
	  <div class="visible">
	    <div class="agregados">
		<b>Buscar proveedor: </b>
		<form action="consulta.php?buscar_proveedor=" method="get">
		<input type="text" onChange="this.form.submit()" name="buscar_proveedor" value="<?php echo $proveedor?>" onBlur="if(this.value=='') this.value='Buscar...';" onFocus="if(this.value=='Buscar...') this.value='';"/>
		<a href="#" onClick="this.form.submit()">Buscar <img src="img/buscar.png" width="20px" height="20px" style="vertical-align: middle;"/></a>
		</form>
		<?php if (isset($_GET['buscar_proveedor'])) { ?>
			<?php if ($total_filas_resultado_proveedores_busca==0) { ?>
				No se encontro ningun proveedor .
			<?php } else { ?>

		<div>	
			<table>
			   <tr>
				<td>Codigo</td>
				<td>Nombre</td>
				<td>NIF/CIF</td>
				<td>Numero de cuenta</td>
			   </tr>
			<?php while ($filas_resultado_proveedores_busca= $resultado_proveedores_busca->fetch_assoc()) { ?>
			   <tr class="fondonegro">
				<td><?php echo $filas_resultado_proveedores_busca['CodPro'];?></td>
				<td><?php echo $filas_resultado_proveedores_busca['NomPro'];?></td>
				<td><?php echo $filas_resultado_proveedores_busca['NIF_CIF'];?></td>
				<td>(<?php echo $filas_resultado_proveedores_busca['nombre'];?>) <?php echo $filas_resultado_proveedores_busca['NumCue'];?></td>
			   </tr>
			
	
			<?php } ?>
			</table>
		</div>
		<?php } } ?>
		
	  </div>
	</div>
	<!-- FIN buscar proveedores -->
<!-- FIN buscar proveedores -->	
<hr />
<!-- INICIO FACTURAS -->
	<p><b>Consultar tickets de venta al publico</b></p>
<a href="#" onClick="mostrar_eliminar_proveedor()" id="boton_eliminar_proveedor">Todos los tickets:<img src="img/actualizar.png" width="20px" height="20px" style="vertical-align: middle;"/></a>
	  <div class="invisible" id="eliminar_proveedor">
		   <div class="agregados">
		
		<span style="float: right;"><input  class="botonesrojos" type="button" value="X" onClick="ocultar_eliminar_proveedor()" /></span>
		<b>Todos los tickets: </b>
		<br />
	<?php   $repetido=-1; 
	 while ($filas_faccli= $resultado_faccli->fetch_assoc()) {
	         if ($repetido!= $filas_faccli['CodFac']) {  ?>
			   <?php if ($repetido!=-1) echo "</table><hr />" ?>
			<table>
				<tr>
				
				<td><b>Ticket numero: <?php echo$filas_faccli['CodFac'];?> </b> </td>
				<td>Fecha: <?php echo$filas_faccli['fecha'];?></td>
				<td><a href="imprimirotra.php?otrocodigo=<?php echo$filas_faccli['CodFac'];?>">Imprimir</a></td>
				<td><a href="imprimirotraregalo.php?otrocodigo=<?php echo$filas_faccli['CodFac'];?>">Ticket regalo</a></td>									
				</tr>

				<tr>
				<td>Articulo</td>
				<td>Nombre</td>
				<td>Precio</td>
				<td>Cantidad</td>
				</tr>

				<?php $repetido=$filas_faccli['CodFac']; } ?>
				<tr class="fondonegro">
				<td><?php echo $filas_faccli['CodArt'];?></td>
				<td><?php echo $filas_faccli['NomArt'];?> </td>
				<td><?php echo $filas_faccli['precio'];?><?php echo $sesion->moneda;?> </td>
				<td><?php echo $filas_faccli['cantidad'];?></td>
						
				</tr>
		<?php } ?><?php if ($repetido!=-1) echo "</table><hr />" ?>
		<br />
		
	</div>
	</div>
<br /><br />
<!--buscar facturas -->
	 <div class="visible">
	    <div class="agregados">
		<b>Buscar Ticket: </b>
		<form action="consulta.php?buscar_faccli=" method="get">
		<input type="text" onChange="this.form.submit()" name="buscar_faccli" value="<?php echo $faccli?>" onBlur="if(this.value=='') this.value='Buscar...';" onFocus="if(this.value=='Buscar...') this.value='';"/>
		<a href="#" onClick="this.form.submit()">Buscar <img src="img/buscar.png" width="20px" height="20px" style="vertical-align: middle;"/></a>
		</form>
		<?php if (isset($_GET['buscar_faccli'])) { ?>
			<?php if ($total_filas_resultado_faccli_busca==0) { ?>
				No se encontro ningun ticket con ese codigo o fecha .
			<?php } else { ?>
		
	<?php   $repetido2=-1; 
	 while ($filas_faccli_busca= $resultado_faccli_busca->fetch_assoc()) {
	         if ($repetido2!= $filas_faccli_busca['CodFac']) {  ?>
			   <?php if ($repetido2!=-1) echo "</table><hr />" ?>
			<table>
				<tr>
				
				<td><b>Ticket numero: <?php echo $filas_faccli_busca['CodFac'];?> </b> </td>
				<td>Fecha: <?php echo $filas_faccli_busca['fecha'];?></td>
				<td><a href="imprimirotra.php?otrocodigo=<?php echo $filas_faccli_busca['CodFac'];?>">Imprimir</a></td>	
           		<td><a href="imprimirotraregalo.php?otrocodigo=<?php echo $filas_faccli_busca['CodFac'];?>">Ticket regalo</a></td>									
				</tr>

				<tr>
				<td>Articulo</td>
				<td>Nombre</td>
				<td>Precio</td>
				<td>Cantidad</td>
				</tr>

				<?php $repetido2=$filas_faccli_busca['CodFac']; } ?>
				<tr class="fondonegro">
				<td><?php echo $filas_faccli_busca['CodArt'];?></td>
				<td><?php echo $filas_faccli_busca['NomArt'];?> </td>
				<td><?php echo $filas_faccli_busca['precio'];?><?php echo $sesion->moneda;?> </td>
				<td><?php echo $filas_faccli_busca['cantidad'];?></td>
						
				</tr>
		<?php } ?><?php if ($repetido2!=-1) echo "</table><hr />" ?>
		<br />
		
	<?php } } ?>		
	     </div>
	</div>

<!--FIN buscar facturas -->


<!-- fin facturas -->


<!-- INICIO FACTURAS COMPRAS-->
	<p><b>Consultar facturas de Compra</b></p>
<a href="#" onClick="mostrar_eliminar_banco()" id="boton_eliminar_banco">Todas las facturas:<img src="img/actualizar.png" width="20px" height="20px" style="vertical-align: middle;"/></a>
	  <div class="invisible" id="eliminar_banco">
		   <div class="agregados">
		
		<span style="float: right;"><input  class="botonesrojos" type="button" value="X" onClick="ocultar_eliminar_banco()" /></span>
		<b>Todas las facturas: </b>
		<br />
	<?php   $repetido=-1; 
	 while ($filas_facpro= $resultado_facpro->fetch_assoc()) {
	         if ($repetido!= $filas_facpro['CodFac']) {  ?>
			   <?php if ($repetido!=-1) echo "</table><hr />" ?>
			<table>
				<tr>
				
				<td><b>Codigo Interno <?php echo$filas_facpro['CodFac'];?> </b> </td>
                <td><b><?php echo $filas_facpro['NomPro'];?></b></td>
                <td><b><?php echo $filas_facpro['albaran_factura'];?></b> <?php echo $filas_facpro['NumeroFactura'];?></td>
				<td>Fecha: <?php echo $filas_facpro['fecha'];?></td>
				<td><a href="imprimircompraotra.php?otrocodigo=<?php echo$filas_facpro['CodFac'];?>">Imprimir</a></td>									
				</tr>

				<tr>
					<td>Articulo</td>
					<td>Nombre</td>
                 	<td>Descripcion</td>
					<td>Precio</td>
					<td>Cantidad</td>
				</tr>

				<?php $repetido=$filas_facpro['CodFac']; } ?>
				<tr class="fondonegro">
					<td><?php echo $filas_facpro['CodArt'];?></td>
					<td><?php echo $filas_facpro['NomArt'];?> </td>
             	    <td><?php echo $filas_facpro['DesArt'];?> </td>
					<td><?php echo $filas_facpro['PreCom'];?><?php echo $sesion->moneda;?> </td>
					<td><?php echo $filas_facpro['cantidad'];?></td>
                
				</tr>
		<?php } ?><?php if ($repetido!=-1) echo "</table><hr />" ?>
		<br />
		
	</div>
	</div>
<br /><br />
<!--buscar facturas -->
	 <div class="visible">
	    <div class="agregados">
		<b>Buscar factura: </b>
		<form action="consulta.php?buscar_facpro=" method="get">
		<input type="text" onChange="this.form.submit()" name="buscar_facpro" value="<?php echo $facpro?>" onBlur="if(this.value=='') this.value='Buscar...';" onFocus="if(this.value=='Buscar...') this.value='';"/>
		<a href="#" onClick="this.form.submit()">Buscar <img src="img/buscar.png" width="20px" height="20px" style="vertical-align: middle;"/></a>
		</form>
		<?php if (isset($_GET['buscar_facpro'])) { ?>
			<?php if ($total_filas_resultado_facpro_busca==0) { ?>
				No se encontro ninguna factura con ese codigo o fecha .
			<?php } else { ?>
		
	<?php   $repetido2=-1; 
	 while ($filas_facpro_busca= $resultado_facpro_busca->fetch_assoc()) {
	         if ($repetido2!= $filas_facpro_busca['CodFac']) {  ?>
			   <?php if ($repetido2!=-1) echo "</table><hr />" ?>
			<table>
				<tr>
				
				<td><b>Codigo Interno: <?php echo $filas_facpro_busca['CodFac'];?> </b> </td>
                <td><b><?php echo $filas_facpro_busca['NomPro'];?></b></td>
                <td><b><?php echo $filas_facpro_busca['albaran_factura'];?></b> <?php echo $filas_facpro_busca['NumeroFactura'];?></td>
                <td>Fecha: <?php echo $filas_facpro_busca['fecha'];?></td>

				<td><a href="imprimircompraotra.php?otrocodigo=<?php echo$filas_facpro_busca['CodFac'];?>">Imprimir</a></td>									
				</tr>

				<tr>
					<td>Articulo</td>
					<td>Nombre</td>
                	<td>Descripcion</td>
					<td>Precio</td>
					<td>Cantidad</td>

				</tr>

				<?php $repetido2=$filas_facpro_busca['CodFac']; } ?>
				<tr class="fondonegro">
					<td><?php echo $filas_facpro_busca['CodArt'];?></td>
					<td><?php echo $filas_facpro_busca['NomArt'];?> </td>
                    <td><?php echo $filas_facpro_busca['DesArt'];?> </td>
					<td><?php echo $filas_facpro_busca['PreCom'];?><?php echo $sesion->moneda;?> </td>
					<td><?php echo $filas_facpro_busca['cantidad'];?></td>
					
                
				</tr>
		<?php } ?><?php if ($repetido2!=-1) echo "</table><hr />" ?>
		<br />
		
	<?php } } ?>		
	     </div>
	</div>

<!--FIN buscar facturas compras -->


<!-- fin facturas compras-->
</div>
</body>
</html>
