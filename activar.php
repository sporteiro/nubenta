<?php
declare(strict_types=1);
require_once('clases/cargador.php');
$conectarBD = new conexion();
$conn = $conectarBD->getConexion();

if (isset($_GET['codigo'])) {
	$todo = $_GET['codigo'];
	$explotar = explode("-", $todo);
	$usuario = $conn->real_escape_string($explotar[0]);
	$codigo = $conn->real_escape_string($explotar[1]);

	try {
		$stmt = $conn->prepare("SELECT * FROM andrea WHERE usuario = ? AND activo = ?");
		$stmt->bind_param('ss', $usuario, $codigo);
		$stmt->execute();
		$resultado = $stmt->get_result();
		$filas = $resultado->fetch_assoc();
		$numerofilas = $resultado->num_rows;

		if ($numerofilas > 0) {
			$stmt2 = $conn->prepare("UPDATE andrea SET activo = 'si' WHERE usuario = ? AND activo = ?");
			$stmt2->bind_param('ss', $usuario, $codigo);
			$stmt2->execute();
			$stmt2->close();

			echo "cuenta activada";
			$sesion = new sesion();
			header('Location: activexito.php');
		} else {
			header('Location: index.php');
			$r = "error";
		}
		$stmt->close();
	} catch (Exception $r) {
		echo "error";
	}
} else {
	echo "No se pudo activar nada";
}
?>


