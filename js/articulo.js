/*articulos*/

function mostrar_nuevo_articulo() {
	document.getElementById("nuevo_articulo").className='visible';
	document.getElementById("boton_nuevo_articulo").className='invisible';	
}

function ocultar_nuevo_articulo() {
	document.getElementById("nuevo_articulo").className='invisible';
	document.getElementById("boton_nuevo_articulo").className='botones';
	
}

function mostrar_actualizar_articulo() {
	document.getElementById("actualizar_articulo").className='visible';
	document.getElementById("boton_actualizar_articulo").className='invisible';	
}

function ocultar_actualizar_articulo() {
	document.getElementById("actualizar_articulo").className='invisible';
	document.getElementById("boton_actualizar_articulo").className='botones';
	
}


function mostrar_eliminar_articulo() {
	document.getElementById("eliminar_articulo").className='visible';
	document.getElementById("boton_eliminar_articulo").className='invisible';	
}

function ocultar_eliminar_articulo() {
	document.getElementById("eliminar_articulo").className='invisible';
	document.getElementById("boton_eliminar_articulo").className='botones';
	
}

/*fin articulos*/



/*familias*/
function mostrar_nuevo_familia() {
	document.getElementById("nuevo_familia").className='visible';
	document.getElementById("boton_nuevo_familia").className='invisible';	
}

function ocultar_nuevo_familia() {
	document.getElementById("nuevo_familia").className='invisible';
	document.getElementById("boton_nuevo_familia").className='botones';
	
}

function mostrar_actualizar_familia() {
	document.getElementById("actualizar_familia").className='visible';
	document.getElementById("boton_actualizar_familia").className='invisible';	
}

function ocultar_actualizar_familia() {
	document.getElementById("actualizar_familia").className='invisible';
	document.getElementById("boton_actualizar_familia").className='botones';
	
}


function mostrar_eliminar_familia() {
	document.getElementById("eliminar_familia").className='visible';
	document.getElementById("boton_eliminar_familia").className='invisible';	
}

function ocultar_eliminar_familia() {
	document.getElementById("eliminar_familia").className='invisible';
	document.getElementById("boton_eliminar_familia").className='botones';
	
}

/*fin familias*/





/* INICIO RESTRICCIONES FORMULARIO Familias*/

function comprobar_campos_familia()	{
	var form_familia="";
		
	if (document.getElementById("Inombre").value=="") {
		form_familia+="Falta el Nombre\n";
		document.getElementById("Inombre").className='botonesrojos';
	}

	if (form_familia!="") {
			alert("Comprobar los siguientes errores: \n\n"+form_familia);
		}
	 
	else	{
			document.getElementById("agregar_familia").submit();
		}
}

/* FIN RESTRICCIONES FORMULARIO familias */



/* INICIO RESTRICCIONES FORMULARIO articulos*/

function comprobar_campos_articulo()	{
	var form_articulo="";
		
	if (document.getElementById("INomArt").value=="") {
		form_articulo+="Falta el Nombre\n";
		document.getElementById("INomArt").className='botonesrojos';
	}
	if (isNaN(document.getElementById("Iprecio").value)==true) {
		form_articulo+="El precio debe ser un numero\n";
		document.getElementById("Iprecio").className='botonesrojos';
							
	}
	if (form_articulo!="") {
			alert("Comprobar los siguientes errores: \n\n"+form_articulo);
		}
	 
	else	{
			document.getElementById("agregar_articulo").submit();
		}
}

/* FIN RESTRICCIONES FORMULARIO articulos*/
