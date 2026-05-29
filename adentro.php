<?php
declare(strict_types=1);
include_once('clases/cargador.php');
$sesion = new sesion();

if (!isset($sesion)||($sesion->conectado==false)) {
        header("Location: index.php");
}
        else {

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="estilo.css" />
<link rel="shortcut icon" href="favicon.ico"/>
<title>Nubenta TPV desde la nube</title>
<script type="text/javascript" src="js/js.js"></script>
</head>
<body>
        <div class="titulo"><img src="img/logo.png" widht="200px" height="80" alt="nubenta"/></div>

<!-- INICIO Usuari@ conectad@ -->
        <div class="administrador">
        <form name="desconectarse" action="ingresar.php" method="post">
                Bienvenido <?php echo $sesion->usuario ?>
                <input type="submit" class="botones" value="Desconectarse"/><input type="hidden" name="accion" value="desconectarse"/>
        </form>
        </div>
 </div>
<!-- FIN Usuari@ conectad@ -->
        <br />
<!-- INICIO CONTENEDORA -->

        <div id="concuchillo" class="concuchillo">
        

        <!-- INICIO BOTONERA -->

                <div id="botonera" class="botonera">
                <a href="#" onclick="mostrarVE()" id="b1">Vender</a> <a href="#" onclick="mostrarPR()" id="b2">Proveedor/Banco</a>
                <a href="#" onclick="mostrarAR()" id="b3">Articulo/Familia</a>  <a href="#" onclick="mostrarCO()" id="b5">Consultas</a>
                <a href="#" onclick="mostrarFA()" id="b4">Stock</a> <a href="#" onclick="mostrarCC()" id="b6">Comprar</a> 
                <a href="#" onclick="mostrarCT()" id="b7">Contabilidad</a> <a href="#" onclick="mostrarMA()" id="b8">Configuracion</a> 
                </div>

        <!-- FIN BOTONERA -->
 <!-- INICIO CONTENIDO -->
                <div id="contenido" class="contenido">
                </div>  
        <!-- FIN CONTENIDO -->

        </div>

<!-- FIN CONTENEDORA -->

</body>
</html>
<?php } ?>

