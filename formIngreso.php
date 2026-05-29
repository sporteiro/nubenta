<div class="ventana">
   <div class="cerrar">
                <img src="img/cerrar.png" height="24" width="24" style="cursor: pointer;" onclick="cancelar()"/>
        </div>
        <div style="clear:both;"><br /></div>
        Acceso usuarios registrados
                <form name="login" id="login" action="ingresar.php" method="post">
                        <p>Nombre de usuario <input type="text" value="" name="usuario" id="usuario" size="17" maxlength="12"/></p>
                        <p>Contrase&ntilde;a <input type="password" value="" name="contrasena" id="contrasena" size="17" maxlength="12"/></p>
                        <input type="hidden" name="accion" value="login"/>
                        <p><input type="button" value="Entrar" class="botones" onclick="comprobar()" /> </p>
                </form>
                <div id="oculto" class="error">
                </div>
        <div style="clear:both;"><br /></div>
</div>     
