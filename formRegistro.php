<div class="ventana" style="height:450px; width:500px;">
   <div class="cerrar">
                <img src="img/cerrar.png" height="24" width="24" style="cursor: pointer;" onclick="cancelar()"/>
        </div><br />
		<form name="registro" id="registro" action="ingresar.php" method="post">
			<p>Nombre de usuario <input type="text" value="" size="20" maxlength="30" name="rusuario" id="rusuario"/> <input type="button" class="botones" value="Comprobar" id="Brusuario"  onclick="comprobarnombre()"/> 
			</p>
			<div id="error2" class="error"></div>
			<div id="resto" class="invisible">
				<p>Nombre <input type="text" name="nombre" id="nombre" size="30" maxlength="30"/></p>
				<p>Apellido <input type="text" name="apellido" id="apellido" size="30" maxlength="30"/></p>
				<p>Email <input type="email" name="email" id="email" size="30" maxlength="50"/></p>
			
				<p>Escriba una contrase&ntilde;a <input type="password" name="rcontrasena" id="rcontrasena" value=""  size="20" maxlength="20" /></p>
				<div id="error" class="error"></div>
				<p>Repita la contrase&ntilde;a <input type="password" name="rcontrasena2" id="rcontrasena2" size="20" value="" maxlength="20" /></p>
				<div id="error" class="error"></div>
				<input type="hidden" name="accion" value="registro"/>
				<p><input type="button" value="Anular" class="botones" onclick="comprobarNuevo()"/>  <input type="button" value="Registrarse" class="botones" onclick="compreg()" id="registrarse" name="registrase"/></p>
			</div>
		</form>
	</div>	
</div>
