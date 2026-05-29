<?php

declare(strict_types=1);

class email {
	private string $origen;
	private string $ip;
	private string $usuario;
	private string $nombre;
	private string $activo;

	public function __construct() {
		$this->origen = 'Nubenta.com.ar';
		$this->ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
	}

	public function enviarUsuario(string $rusuario, string $nombre, string $email, string $activo): bool {
		$mensaje = "<html><div style='margin:0 auto; width:400px;'><img src='http://nubenta.com.ar/img/logo.png' width='200' height='80' alt='nubenta'/>
<hr /><div>Estimado: " . htmlspecialchars($nombre) . "<br />Gracias por registrarse en Nubenta <br /> Esta a un paso de poder disfrutar de un completo sistema<br/> de terminal de punto de venta a traves de internet<br /><p>Para activar su cuenta, haga click en el siguiente enlace:<br /><a href='http://nubenta.com.ar/activar.php?codigo=" . htmlspecialchars($rusuario) . "-" . htmlspecialchars($activo) . "'> Activar cuenta</a></p><p>Sus datos de acceso son:</p><p>Usuario: " . htmlspecialchars($rusuario) . "<br />Nombre: " . htmlspecialchars($nombre) . "<br />Email: " . htmlspecialchars($email) . " </p></div><hr />
Dise&ntilde;o y desarrollo del sitio: <a href='http://sebastianporteiro.com.ar'>Sebastian Porteiro </a> <img src='http://nubenta.com.ar/img/sebastianporteiro.ico' alt='sebastianporteiro.com.ar'/><br /></div></html>";

		$cabecera = "MIME-Version: 1.0\r\n";
		$cabecera .= "Content-type: text/html; charset=UTF-8\r\n";
		$cabecera .= "From: " . $this->origen . "\r\n";

		return mail($email, "Bienvenido a Nubenta", $mensaje, $cabecera);
	}

	public function enviarAdmin(string $rusuario, string $nombre, string $email, string $ip): bool {
		$mensaje = "<div>usuario: " . htmlspecialchars($rusuario) . " <br />nombre: " . htmlspecialchars($nombre) . "<br />email: " . htmlspecialchars($email) . "<br />ip: " . htmlspecialchars($ip) . "</div>";

		$cabecera = "MIME-Version: 1.0\r\n";
		$cabecera .= "Content-type: text/html; charset=UTF-8\r\n";
		$cabecera .= "From: " . $this->origen . "\r\n";

		return mail('seblash@gmail.com', "Alguien se registro en Nubenta", $mensaje, $cabecera);
	}

	public function contacto(string $Cnombre, string $Capellido, string $Cemail, string $Cmensaje, string $Cip): bool {
		$mensaje = "<div>nombre: " . htmlspecialchars($Cnombre) . " <br />apellido: " . htmlspecialchars($Capellido) . "<br />email: " . htmlspecialchars($Cemail) . "<br />Mensaje: " . htmlspecialchars($Cmensaje) . " <br />ip: " . htmlspecialchars($Cip) . "</div>";

		$cabecera = "MIME-Version: 1.0\r\n";
		$cabecera .= "Content-type: text/html; charset=UTF-8\r\n";
		$cabecera .= "From: " . $this->origen . "\r\n";

		return mail('seblash@gmail.com', "Contacto de Nubenta", $mensaje, $cabecera);
	}
}
