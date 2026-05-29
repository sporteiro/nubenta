<?php

declare(strict_types=1);

function CargarClase(string $nombre): void {
	$archivo = __DIR__ . '/' . $nombre . '.php';
	if (file_exists($archivo)) {
		include_once $archivo;
	}
}
spl_autoload_register('CargarClase');
