<?php

declare(strict_types=1);

session_start();

class sesion {
	public ?int $CodUsu = null;
	public ?string $usuario = null;
	public ?string $nombre = null;
	private ?string $contrasena = null;
	public bool $conectado = false;
	public ?string $activo = null;
	public ?string $moneda = null;

	public function __construct() {
		if (isset($_SESSION['usuario'])) {
			$this->conectado = true;
			$this->usuario = $_SESSION['usuario'];
			$this->contrasena = $_SESSION['contrasena'];
			$this->CodUsu = $_SESSION['CodUsu'];
			$this->nombre = $_SESSION['nombre'];
			$this->moneda = $_SESSION['moneda'];
			$this->activo = 'si';
		}
	}

	public function conectarse(string $usuario, string $contrasena): bool {
		$conectarseLogin = new conexion();
		$conn = $conectarseLogin->getConexion();

		$stmt = $conn->prepare('SELECT * FROM andrea WHERE usuario = ? AND contrasena = ?');
		$stmt->bind_param('ss', $usuario, $contrasena);
		$stmt->execute();
		$resultado = $stmt->get_result();
		$filas_consulta = $resultado->fetch_assoc();
		$numero_de_filas = $resultado->num_rows;

		if ($numero_de_filas > 0 && $filas_consulta['usuario'] === $usuario && $filas_consulta['contrasena'] === $contrasena) {
			if ($filas_consulta['activo'] === 'si') {
				$_SESSION['usuario'] = $usuario;
				$_SESSION['contrasena'] = $contrasena;
				$_SESSION['nombre'] = $filas_consulta['nombre'];
				$_SESSION['CodUsu'] = $filas_consulta['CodUsu'];
				$_SESSION['moneda'] = $filas_consulta['moneda'];

				$this->conectado = true;
				$this->activo = 'si';
				$this->usuario = $_SESSION['usuario'];
				$this->contrasena = $_SESSION['contrasena'];
				$this->CodUsu = $_SESSION['CodUsu'];
				$this->nombre = $_SESSION['nombre'];
				$this->moneda = $_SESSION['moneda'];
			} else {
				$this->conectado = true;
				$this->activo = 'no';
			}
		}

		$stmt->close();
		return $this->conectado;
	}

	public function desconectarse(): void {
		session_unset();
		session_destroy();
		$this->conectado = false;
	}

	public function comprobarUsuario(string $rusuario): bool {
		$conectarseAlaBD = new conexion();
		$conn = $conectarseAlaBD->getConexion();

		$stmt = $conn->prepare('SELECT usuario FROM andrea WHERE usuario = ?');
		$stmt->bind_param('s', $rusuario);
		$stmt->execute();
		$resultado = $stmt->get_result();
		$numeroFilas = $resultado->num_rows;

		$stmt->close();
		return $numeroFilas > 0;
	}

	public function registro(string $nombrereal): bool {
		$conectarDB2 = new conexion();
		$conn = $conectarDB2->getConexion();

		$rusuario = $conn->real_escape_string($_POST['rusuario'] ?? '');
		$rcontrasena = $conn->real_escape_string($_POST['rcontrasena'] ?? '');
		$nombre = $conn->real_escape_string($_POST['nombre'] ?? '');
		$apellido = $conn->real_escape_string($_POST['apellido'] ?? '');
		$email = $conn->real_escape_string($_POST['email'] ?? '');
		$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
		$activo = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', 105)), 25, 25);

		$stmt = $conn->prepare('INSERT INTO andrea(usuario, contrasena, nombre, apellido, email, ip, activo, moneda, fecha_accesos, fecha_desconexion) VALUES (?, ?, ?, ?, ?, ?, ?, "$", NOW(), NOW())');
		$stmt->bind_param('sssssss', $rusuario, $rcontrasena, $nombre, $apellido, $email, $ip, $activo);

		if (!$stmt->execute()) {
			die('Error en registro: ' . $stmt->error);
		}

		$stmt->close();

		$stmt2 = $conn->prepare('INSERT INTO datos_empresa(CodUsu) SELECT MAX(CodUsu) FROM andrea');
		if (!$stmt2->execute()) {
			die('Error en datos_empresa: ' . $stmt2->error);
		}
		$stmt2->close();

		$correo = new email();
		$correo->enviarUsuario($rusuario, $nombre, $email, $activo);
		$correo->enviarAdmin($rusuario, $nombre, $email, $ip);

		return true;
	}

	public function modificar(string $nombre): bool {
		$conectarDB3 = new conexion();
		$conn = $conectarDB3->getConexion();

		$usuario = $conn->real_escape_string($_POST['usuario'] ?? '');
		$nombre = $conn->real_escape_string($_POST['nombre'] ?? '');
		$apellido = $conn->real_escape_string($_POST['apellido'] ?? '');
		$email = $conn->real_escape_string($_POST['email'] ?? '');
		$cnueva = $conn->real_escape_string($_POST['cnueva'] ?? '');
		$forpago = $conn->real_escape_string($_POST['forpago'] ?? '');

		$stmt = $conn->prepare('UPDATE usuario SET nombre = ?, apellido = ?, email = ?, contrasena = ?, forpago = ? WHERE usuario = ?');
		$stmt->bind_param('ssssss', $nombre, $apellido, $email, $cnueva, $forpago, $usuario);

		if (!$stmt->execute()) {
			die('Error en modificación: ' . $stmt->error);
		}

		$stmt->close();

		$_SESSION['usuario'] = $_POST['usuario'] ?? '';
		$_SESSION['nombre'] = $_POST['nombre'] ?? '';
		$_SESSION['apellido'] = $_POST['apellido'] ?? '';
		$_SESSION['email'] = $_POST['email'] ?? '';
		$_SESSION['forpago'] = $_POST['forpago'] ?? '';
		$_SESSION['contrasena'] = $_POST['cnueva'] ?? '';

		$this->usuario = $_SESSION['usuario'];
		$this->contrasena = $_SESSION['contrasena'];
		$this->nombre = $_SESSION['nombre'];
		$this->apellido = $_SESSION['apellido'] ?? '';
		$this->email = $_SESSION['email'] ?? '';
		$this->forpago = $_SESSION['forpago'] ?? '';

		return true;
	}
}

