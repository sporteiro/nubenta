function mostrarIngreso() {
	ajax('post','formIngreso.php',false,'info',verformingreso);
}
function verformingreso(id,formulario) {
	var divajax=document.getElementById('ajax')
	divajax.className='oscurecer';
	divajax.innerHTML=formulario;
}
function cancelar() {
	var divajax=document.getElementById('ajax')
	divajax.className='invisible';
	divajax.innerHTML="";
	
	}
function login(id,todobien) {
	if (todobien==1) {
		location.href='ingresar.php';
	}
	else if (todobien==2) {
		document.getElementById('oculto').innerHTML="Su cuenta no fue activada todavia. Revise su email.";	
	}
	else {
		document.getElementById('oculto').innerHTML="Usuario o contrase&ntilde;a incorrectos";			
	};
}
function comprobar() {
	var usuario=document.getElementById('usuario').value;
	var contrasena=document.getElementById('contrasena').value;
	if (usuario=='')	{
		document.getElementById('oculto').innerHTML="Escriba su nombre de usuario";
	} 
	else
	if (contrasena==''){
		document.getElementById('oculto').innerHTML="Escriba una contrase&ntilde;a";
	}
	else {
	 ajax('post','ingresar.php?accion=login&usuario='+usuario+"&contrasena="+contrasena,false,'oculto',login);
	}
}
function mostrarTutorial() {
	ajax('post','tutorial.php',false,'info',vertutorial);
}
function vertutorial(id,tutorial) {
	var divajax=document.getElementById('ajax')
	divajax.className='oscurecer';
	divajax.innerHTML=tutorial;
}
function mostrarCondiciones() {
	ajax('post','condiciones.php',false,'info',verCondiciones);
}
function verCondiciones(id,condi) {
	var divajax=document.getElementById('ajax')
	divajax.className='oscurecer';
	divajax.innerHTML=condi;
}
function mostrarContacto() {
	ajax('post','contacto.php',false,'info',verContacto);
}
function verContacto(id,contact) {
	var divajax=document.getElementById('ajax')
	divajax.className='oscurecer';
	divajax.innerHTML=contact;
}
