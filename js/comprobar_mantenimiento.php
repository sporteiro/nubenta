<?php
declare(strict_types=1);
require_once('../clases/cargador.php');
$sesion = new sesion();
$conectarBD = new conexion();
$conn = $conectarBD->getConexion();

if (!$sesion->conectado) {
	header("location: index.php");
}

//Consultar Facturas proveedores
$stmt = $conn->prepare("SELECT * FROM facpro WHERE albaran_factura='albaran' AND CodUsu = ?");
$stmt->bind_param('s', $sesion->CodUsu);
$stmt->execute();
$resultado_facpro = $stmt->get_result();

while ($filas_facpro = $resultado_facpro->fetch_assoc()) { ?>
 
function comprobar_factura<?php echo $filas_facpro['CodFac'];?>()	{
	var form_factura="";
		
	if (document.getElementById("N<?php echo $filas_facpro['CodFac'];?>").value=="") {
		form_factura+="Falta el numero de factura del albaran <?php echo $filas_facpro['NumeroFactura'];?>\n";
		document.getElementById("N<?php echo $filas_facpro['CodFac'];?>").className='botonesrojos';
	}
	if (form_factura!="") {
			alert("Comprobar los siguientes errores: \n\n"+form_factura);
		}
	 
	else	{
			document.getElementById("cambiar<?php echo $filas_facpro['CodFac'];?>").submit();
		}
}

<?php } ?>
