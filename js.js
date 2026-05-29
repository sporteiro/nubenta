function mostrarVE () {
		var contenido=document.getElementById('contenido');
		contenido.innerHTML="<iframe src='vender.php' frameborder='0' scrolling='yes' marginheight='0' marginwidth='0' width='900px' height='600px'></iframe>";
		var b1=document.getElementById('b1');
		b1.style.backgroundColor="black";

		var b2=document.getElementById('b2');
		b2.style.backgroundColor="blue";
		
		var b3=document.getElementById('b3');
		b3.style.backgroundColor="blue";
		
		var b4=document.getElementById('b4');
		b4.style.backgroundColor="blue";
		
}


	function mostrarPR () {
		var contenido=document.getElementById('contenido');
		contenido.innerHTML="<iframe src='proveedor.php' frameborder='0' scrolling='yes' marginheight='0' marginwidth='0' width='900px' height='600px'></iframe>";
		var b2=document.getElementById('b2');
		b2.style.backgroundColor="black";
		
		var b1=document.getElementById('b1');
		b1.style.backgroundColor="blue";
		
		var b3=document.getElementById('b3');
		b3.style.backgroundColor="blue";
		
		var b4=document.getElementById('b4');
		b4.style.backgroundColor="blue";
}

	function mostrarAR () {
		var contenido=document.getElementById('contenido');
		contenido.innerHTML="<iframe src='articulo.php' frameborder='0' scrolling='yes' marginheight='0' marginwidth='0' width='900px' height='600px'></iframe>";
		var b3=document.getElementById('b3');
		b3.style.backgroundColor="black";
		
		var b1=document.getElementById('b1');
		b1.style.backgroundColor="blue";
		
		var b2=document.getElementById('b2');
		b2.style.backgroundColor="blue";
		
		var b4=document.getElementById('b4');
		b4.style.backgroundColor="blue";
}

	function mostrarFA () {
		var contenido=document.getElementById('contenido');
		contenido.innerHTML="<iframe src='factura.php' frameborder='0' scrolling='yes' marginheight='0' marginwidth='0' width='900px' height='600px'></iframe>";
		var b4=document.getElementById('b4');
		b4.style.backgroundColor="black";
		
		var b1=document.getElementById('b1');
		b1.style.backgroundColor="blue";
		
		var b2=document.getElementById('b2');
		b2.style.backgroundColor="blue";
		
		var b3=document.getElementById('b3');
		b3.style.backgroundColor="blue";
}

function mostrar_nuevo_proveedor() {
	document.getElementById("nuevo_proveedor").className='visible';
	document.getElementById("boton_nuevo").className='invisible';	
}

function ocultar_nuevo_proveedor() {
	document.getElementById("nuevo_proveedor").className='invisible';
	document.getElementById("boton_nuevo").className='botones';
	
}




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

