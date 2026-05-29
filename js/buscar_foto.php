<?php
declare(strict_types=1);
require_once('../clases/cargador.php');
$sesion = new sesion();
$conectarBD = new conexion();
$conn = $conectarBD->getConexion();

if (!$sesion->conectado) {
	header("location: index.php");
}

//Consultar articulos
$stmt = $conn->prepare("SELECT * FROM articulos WHERE CodUsu = ?");
$stmt->bind_param('s', $sesion->CodUsu);
$stmt->execute();
$resultado_articulo = $stmt->get_result();

while ($filas_articulo = $resultado_articulo->fetch_assoc()) { ?>
 
function buscarFoto<?php echo $filas_articulo['CodArt'];?>() {
	var inputarticulo=document.getElementById('CodArt');
	var valorfoto=document.getElementById('foto<?php echo $filas_articulo['CodArt'];?>').value;
	inputarticulo.value=valorfoto;
	document.getElementById("form_buscar").submit();
 }

<?php } ?>
