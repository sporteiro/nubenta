<?php
declare(strict_types=1);
require_once('clases/cargador.php');
$sesion = new sesion();
$conectarBD = new conexion();
$conn = $conectarBD->getConexion();

if (!$sesion->conectado) {
	header("location: index.php");
}

$stmt = $conn->prepare("SELECT * FROM faccli WHERE CodUsu = ? ORDER BY CodArt, fecha");
$stmt->bind_param('s', $sesion->CodUsu);
$stmt->execute();
$resultado_faccli = $stmt->get_result();

$consulta_union = "(SELECT CodArt, CodFac, OrdMov, NomArt, cantidad as 'entra', cantidad=0 as 'sale', stock, fecha, NumeroFactura FROM facpro WHERE CodUsu = ?)
 UNION
(select CodArt, CodFac, OrdMov, NomArt, cantidad=99999,cantidad, stock,fecha, CodFac from faccli WHERE CodUsu = ?) order by CodArt, OrdMov, CodFac";
$stmt2 = $conn->prepare($consulta_union);
$stmt2->bind_param('ss', $sesion->CodUsu, $sesion->CodUsu);
$stmt2->execute();
$resultado_union = $stmt2->get_result();

//BUSCAR POR FECHA
if (isset($_POST["ano"])) {
	$fecha = $conn->real_escape_string($_POST["ano"] . "-" . $_POST["mes"] . "-" . $_POST["dia"]);
}
else {
	$_POST["ano"] = date('Y');
	$_POST["mes"] = date('n');
	$_POST["dia"] = date('j');
	$fecha = $conn->real_escape_string($_POST["ano"] . "-" . $_POST["mes"] . "-" . $_POST["dia"]);
}

$consulta_union2 = "(SELECT CodArt, CodFac, OrdMov, NomArt, cantidad as 'entra', cantidad=0 as 'sale', stock, fecha, NumeroFactura FROM facpro WHERE fecha = ? AND CodUsu = ?)
 UNION
(select CodArt, CodFac, OrdMov, NomArt, cantidad=99999,cantidad, stock,fecha, CodFac from faccli WHERE fecha = ? AND CodUsu = ?)  order by CodArt, OrdMov, CodFac";
$stmt3 = $conn->prepare($consulta_union2);
$stmt3->bind_param('ssss', $fecha, $sesion->CodUsu, $fecha, $sesion->CodUsu);
$stmt3->execute();
$resultado_union2 = $stmt3->get_result();


?>

<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Kamalakshi ::: Usado por <?php echo $_SESSION['nombreusuario'];?></title>
<link rel="stylesheet" type="text/css" href="estilo.css" />
</head>
<body style="background-color: black;">
<div class="iframes">
	<div>
	<form action="factura.php" method="post">
	<p><b>Buscar stock por fecha</b></p>
	Dia:
	<select name="dia">
	<option selected="selected" value="<?php echo $_POST['dia'];?>">
	<?php echo $_POST['dia'];?>
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
	<option selected="selected" value="<?php echo $_POST['mes'];?>">
	<?php echo $nombremes[$_POST['mes']];?>
	</option>
	</select>
	A&ntilde;o:
	<select name="ano">
	<option selected="selected" value="<?php echo $_POST['ano'];?>">
	<?php echo $_POST['ano'];?>
	</option>
	<?php 
	for ($a=2011;$a<2051;$a++) { ?>
	<option><?php echo $a?></option>
	<?php } ?>
	</select>	
	<input type="submit" value="Buscar fecha" class="botones"/>
	</form>
	</div>

	<div>
<?php   $repetido3=-1; 
 while ($filas_union2= $resultado_union2->fetch_assoc()) {
         if ($repetido3!= $filas_union2['CodArt']) {  ?>
   <?php if ($repetido3!=-1) echo "</table>" ?>
		<table>
		<tr class="negro">
		<td><b>Codigo Articulo: <?php echo$filas_union2['CodArt'];?> </b> </td>
		<td colspan="2"><b>Nombre: <?php echo$filas_union2['NomArt'];?></b><br /></td>
		<td colspan="2"></td>
		</tr>
		<tr class="negro">
		<td>Fecha</td>
		<td>Entra</td>
		<td>Sale</td>
		<td>Codigo Factura</td>
		<td>Stock</td>
		</tr>
		<?php $repetido3=$filas_union2['CodArt']; } ?>
		<tr>
		<td><?php $F=$filas_union2['fecha'];
			 echo $F[8].$F[9].'/'.$F[5].$F[6].'/'.$F[0].$F[1].$F[2].$F[3]?></td>	
		<td>+<?php echo $filas_union2['entra'];?></td>	
		<td>-<?php echo $filas_union2['sale'];?></td>	
		<td><?php echo $filas_union2['NumeroFactura'];?></td>	
		<td><?php echo $filas_union2['stock'];?></td>	
		</tr>
<?php } ?><?php if ($repetido3!=-1) echo "</table>" ?>
	
	</div>

<!--<div>
<?php   $repetido2=-1; 
 while ($filas_union= $resultado_union->fetch_assoc()) {
         if ($repetido2!= $filas_union['CodArt']) {  ?>
   <?php if ($repetido2!=-1) echo "</table>" ?>
		<table>
		<tr class="negro">
		<td><b>Codigo Articulo: <?php echo$filas_union['CodArt'];?> </b> </td>
		<td colspan="2"><b>Nombre: <?php echo$filas_union['NomArt'];?></b><br /></td>
		<td colspan="2"></td>
		</tr>
		<tr class="negro">
		<td>Fecha</td>
		<td>Entra</td>
		<td>Sale</td>
		<td>Codigo Factura</td>
		<td>Stock</td>
		</tr>
		<?php $repetido2=$filas_union['CodArt']; } ?>
		<tr>
		<td><?php $F=$filas_union['fecha'];
			 echo $F[8].$F[9].'/'.$F[5].$F[6].'/'.$F[0].$F[1].$F[2].$F[3]?></td>	
		<td><?php echo $filas_union['entra'];?></td>	
		<td><?php echo $filas_union['sale'];?></td>	
		<td><?php echo $filas_union['NumeroFactura'];?></td>	
		<td><?php echo $filas_union['stock'];?></td>	
		</tr>
<?php } ?><?php if ($repetido2!=-1) echo "</table>" ?>
	</div>-->

</div>
</body>
</html>
