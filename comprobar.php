<?php
if (isset($_POST['rusuario']) && ($_POST['rusuario']!='')) {
  if ($sesion->comprobarUsuario($_POST['rusuario'])) {
    echo 1;  
  }
}
?>
