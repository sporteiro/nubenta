<?php

declare(strict_types=1);

class conexion {
	private ?mysqli $conexion = null;
	private string $usuario;
	private string $contrasena;
	private string $host;
	public string $basedatos;

	public function __construct() {
		$raw = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? '';
		$es_local = false;

		if (getenv('ESCRIBILO_LOCAL') === '1' || strtolower((string) getenv('ESCRIBILO_LOCAL')) === 'true') {
			$es_local = true;
		} elseif (strpos($raw, '[::1]') !== false) {
			$es_local = true;
		} else {
			$host = strtolower(trim(explode(':', $raw, 2)[0]));
			if ($host === '' || $host === 'localhost' || $host === '127.0.0.1') {
				$es_local = true;
			} else {
				foreach (['.localhost', '.local', '.test', '.lan', '.dev'] as $suf) {
					if (strlen($host) > strlen($suf) && substr($host, -strlen($suf)) === $suf) {
						$es_local = true;
						break;
					}
				}
				if (!$es_local && filter_var($host, FILTER_VALIDATE_IP)) {
					if (!filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
						$es_local = true;
					}
				}
			}
		}

		if ($es_local) {
			$this->host = 'mysql-server';
			$this->usuario = 'root';
			$this->contrasena = 'rootpassword123';
			$this->basedatos = 'nubenta';
		} else {
			require __DIR__ . '/conexion_remota.php';
			$this->host = $host;
			$this->usuario = $user;
			$this->contrasena = $pass;
			$this->basedatos = $db;
		}

		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

		try {
			$this->conexion = new mysqli($this->host, $this->usuario, $this->contrasena, $this->basedatos);
			mysqli_set_charset($this->conexion, 'latin1');
		} catch (mysqli_sql_exception $e) {
			if ($es_local) {
				die('Error DB local: ' . $e->getMessage());
			}
			header('Location: ../html/404.html');
			exit;
		}
	}

	public function getConexion(): mysqli {
		return $this->conexion;
	}

	public function cerrar(): void {
		if ($this->conexion !== null) {
			$this->conexion->close();
		}
	}

	public function __destruct() {
		$this->cerrar();
	}
}

