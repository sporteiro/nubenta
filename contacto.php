<?php require_once('clases/cargador.php');
if (isset($_POST["email"])) {

$Cnombre=$_POST["nombre"];
$Capellido=$_POST["apellido"];
$Cemail=$_POST["email"];
$Cmensaje=$_POST["mensaje"];
$Cip=$_SERVER['REMOTE_ADDR'];


$emailC=new email();
$emailC->contacto($Cnombre,$Capellido,$Cemail,$Cmensaje,$Cip);

header('Location: index.php');
} 
?>
<div class="ventana" style="height:450px;">
   <div class="cerrar">
                <img src="img/cerrar.png" height="24" width="24" style="cursor: pointer;" onclick="cancelar()"/>
        </div>
        <div style="clear:both;"><br /></div>
	Contacto<br />
		<form name="contacto" id="contacto" action="contacto.php" method="post">
				<p>Nombre <input type="text" name="nombre" id="nombre" size="30" maxlength="30"/></p>
				<p>Apellido <input type="text" name="apellido" id="apellido" size="30" maxlength="30"/></p>
				<p>Email <input type="email" name="email" id="email" size="30" maxlength="50"/></p>
			
				<p>Mensaje: <textarea name="mensaje" id="mensaje" cols="30" rows="5"></textarea></p>
				<p><input type="submit" value="Enviar" class="botones" id="enviar"/></p>
		</form>
        </div>
        <div style="clear:both;"><br /></div>
</div>     
