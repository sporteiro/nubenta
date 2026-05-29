function mostrarRegistro() {
	ajax('post','formRegistro.php',false,'info',verformRegistro);
}
function verformRegistro(id,formulario) {
	var divajax=document.getElementById('ajax')
	divajax.className='oscurecer';
	divajax.innerHTML=formulario;
}
function cancelar() {
	var divajax=document.getElementById('ajax')
	divajax.className='invisible';
	divajax.innerHTML="";
	
	}
function mostrar() {
	document.getElementById("reg").className='visible';


}
function comprobarNuevo() {

	document.getElementById("resto").className='invisible';
	document.getElementById("Brusuario").className='visible';
	document.getElementById("rusuario").removeAttribute("readonly");
	document.getElementById("rusuario").className="normal";
	document.getElementById("Brusuario").className="botones";

}
function compreg() {
		
	var contrasena1=document.getElementById("rcontrasena").value;
	var contrasena2=document.getElementById("rcontrasena2").value;
	if (contrasena1=="") {
		document.getElementById("error").innerHTML="Debe escribir una contrase&ntilde;a";
		
	}
	else {
		if (contrasena1==contrasena2) { 
			document.getElementById('registro').submit();	
		}	 
		else {
			document.getElementById("rcontrasena2").className='rojo';
			document.getElementById("error").innerHTML="Las contrase&ntilde;as no coinciden";
		}
	}

}
function comprobarnombre() {
	var rusuario=document.getElementById('rusuario').value;
	if (rusuario=='') {
		document.getElementById("error2").innerHTML="Debe elegir un nombre de usuario";	
	}
	else {
	 ajax('post','ingresar.php?accion=comprobar&rusuario='+rusuario,false,'oculto',comprobarnombreajax);
	}	

}
function comprobarnombreajax (id,info) {
	if (info==1) {
		document.getElementById("error2").innerHTML="El nombre ya esta registrado";
	 }
	else {
		document.getElementById("error2").innerHTML="El nombre esta disponible";
		var rusuarioinput=document.getElementById('rusuario');
		rusuarioinput.setAttribute('readonly','readonly');
		rusuarioinput.className='celeste';
		document.getElementById('resto').className='visible';	
		document.getElementById('oculto').innerHTML='';
		document.getElementById('Brusuario').className='invisible';
	
	}
}
