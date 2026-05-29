<?php
declare(strict_types=1);
include_once('clases/cargador.php');
$sesion = new sesion();

if (isset($_POST['nombre']) && ($_POST['nombre']!='')) {
  if ($sesion->registro($_POST['nombre'])) {
   header('Location:regexito.php');
  }
  else echo "mal";
}
else echo "mal";
?>
