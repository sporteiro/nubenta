/* INICIO MOSTRAR Y OCULTAR PROVEEDORES */

function mostrar_nuevo_proveedor() {
	document.getElementById("nuevo_proveedor").className='visible';
	document.getElementById("boton_nuevo_proveedor").className='invisible';	
}

function ocultar_nuevo_proveedor() {
	document.getElementById("nuevo_proveedor").className='invisible';
	document.getElementById("boton_nuevo_proveedor").className='botones';
	
}

function mostrar_actualizar_proveedor() {
	document.getElementById("actualizar_proveedor").className='visible';
	document.getElementById("boton_actualizar_proveedor").className='invisible';	
}

function ocultar_actualizar_proveedor() {
	document.getElementById("actualizar_proveedor").className='invisible';
	document.getElementById("boton_actualizar_proveedor").className='botones';
	
}


function mostrar_eliminar_proveedor() {
	document.getElementById("eliminar_proveedor").className='visible';
	document.getElementById("boton_eliminar_proveedor").className='invisible';	
}

function ocultar_eliminar_proveedor() {
	document.getElementById("eliminar_proveedor").className='invisible';
	document.getElementById("boton_eliminar_proveedor").className='botones';
	
}
/* FIN MOSTRAR Y OCULTAR PROVEEDORES */



/* INICIO MOSTRAR Y OCULTAR BANCOS*/
function mostrar_nuevo_banco() {
	document.getElementById("nuevo_banco").className='visible';
	document.getElementById("boton_nuevo_banco").className='invisible';	
}

function ocultar_nuevo_banco() {
	document.getElementById("nuevo_banco").className='invisible';
	document.getElementById("boton_nuevo_banco").className='botones';
	
}

function mostrar_actualizar_banco() {
	document.getElementById("actualizar_banco").className='visible';
	document.getElementById("boton_actualizar_banco").className='invisible';	
}

function ocultar_actualizar_banco() {
	document.getElementById("actualizar_banco").className='invisible';
	document.getElementById("boton_actualizar_banco").className='botones';
	
}


function mostrar_eliminar_banco() {
	document.getElementById("eliminar_banco").className='visible';
	document.getElementById("boton_eliminar_banco").className='invisible';	
}

function ocultar_eliminar_banco() {
	document.getElementById("eliminar_banco").className='invisible';
	document.getElementById("boton_eliminar_banco").className='botones';
	
}
/* FIN MOSTRAR Y OCULTAR BANCOS */






/* INICIO MOSTRAR Y OCULTAR CUENTAS */

function mostrar_nuevo_cuenta() {
	document.getElementById("nuevo_cuenta").className='visible';
	document.getElementById("boton_nuevo_cuenta").className='invisible';	
}

function ocultar_nuevo_cuenta() {
	document.getElementById("nuevo_cuenta").className='invisible';
	document.getElementById("boton_nuevo_cuenta").className='botones';
	
}

function mostrar_actualizar_cuenta() {
	document.getElementById("actualizar_cuenta").className='visible';
	document.getElementById("boton_actualizar_cuenta").className='invisible';	
}

function ocultar_actualizar_cuenta() {
	document.getElementById("actualizar_cuenta").className='invisible';
	document.getElementById("boton_actualizar_cuenta").className='botones';
	
}


function mostrar_eliminar_cuenta() {
	document.getElementById("eliminar_cuenta").className='visible';
	document.getElementById("boton_eliminar_cuenta").className='invisible';	
}

function ocultar_eliminar_cuenta() {
	document.getElementById("eliminar_cuenta").className='invisible';
	document.getElementById("boton_eliminar_cuenta").className='botones';
	
}

/* FIN MOSTRAR Y OCULTAR CUENTAS */





/* INICIO RESTRICCIONES FORMULARIO PROVEEDORES */

function comprobar_campos()	{
	var form_proveedor="";
		
	if (document.getElementById("INomPro").value=="") {
		form_proveedor+="Falta el Nombre\n";
		document.getElementById("INomPro").className='botonesrojos';
	}
	
	if (document.getElementById("INIF/CIF").value=="") {
		form_proveedor+="Falta el NIF/CIF\n";
		document.getElementById("INIF/CIF").className='botonesrojos';			
	}
	
	if (document.getElementById("Iemail").value!=""){

			if ((/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(document.getElementById("Iemail").value))==false) {
				form_proveedor+="El email es incorrecto\n";
				document.getElementById("Iemail").className='botonesrojos';
			}
	}
		
	if (isNaN(document.getElementById("Itelefono").value)==true) {
		form_proveedor+="El numero de telefono es incorrecto\n";
		document.getElementById("Itelefono").className='botonesrojos';
							
	}
	if (form_proveedor!="") {
			alert("Comprobar los siguientes errores: \n\n"+form_proveedor);
		}
	 
	else	{
			document.getElementById("agregar_proveedor").submit();
		}
}








/* FIN RESTRICCIONES FORMULARIO PROVEEDORES */


/* INICIO RESTRICCIONES FORMULARIO BANCOS*/

function comprobar_campos_banco()	{
	var form_banco="";
		
	if (document.getElementById("Inombre").value=="") {
		form_banco+="Falta el Nombre\n";
		document.getElementById("Inombre").className='botonesrojos';
	}

	if (form_banco!="") {
			alert("Comprobar los siguientes errores: \n\n"+form_banco);
		}
	 
	else	{
			document.getElementById("agregar_banco").submit();
		}
}

/* FIN RESTRICCIONES FORMULARIO BANCOS */


/* INICIO RESTRICCIONES FORMULARIO CUENTAS*/

function comprobar_campos_cuenta()	{
	var form_cuenta="";
		
	if (document.getElementById("INumCue").value=="") {
		form_cuenta+="No existe ningun numero\n";
		document.getElementById("INumCue").className='botonesrojos';
	}

	if (form_cuenta!="") {
			alert("Comprobar los siguientes errores: \n\n"+form_cuenta);
		}
	 
	else	{
			document.getElementById("agregar_cuenta").submit();
		}
}

/* FIN RESTRICCIONES FORMULARIO BANCOS */

