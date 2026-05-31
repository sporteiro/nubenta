function mostrarVE () {
		var contenido=document.getElementById('contenido');
		contenido.innerHTML="<iframe src='vender.php' style='overflow-x:hidden; overflow-y:auto;' frameborder='0' scrolling='yes' marginheight='0' marginwidth='0' width='100%' height='600px'></iframe>";
		setActiveTab('b1');
}


	function mostrarPR () {
		var contenido=document.getElementById('contenido');
		contenido.innerHTML="<iframe src='proveedor.php' style='overflow-x:hidden; overflow-y:auto;' frameborder='0' scrolling='yes' marginheight='0' marginwidth='0' width='100%' height='600px'></iframe>";
		setActiveTab('b2');
}

	function mostrarAR () {
		var contenido=document.getElementById('contenido');
		contenido.innerHTML="<iframe src='articulo.php' style='overflow-x:hidden; overflow-y:auto;' frameborder='0' scrolling='yes' marginheight='0' marginwidth='0' width='100%' height='600px'></iframe>";
		setActiveTab('b3');
}

	function mostrarFA () {
		var contenido=document.getElementById('contenido');
		contenido.innerHTML="<iframe src='factura.php' style='overflow-x:hidden; overflow-y:auto;' frameborder='0' scrolling='yes' marginheight='0' marginwidth='0' width='100%' height='600px'></iframe>";
		setActiveTab('b4');
}

	function mostrarCO() {
		var contenido=document.getElementById('contenido');
		contenido.innerHTML="<iframe src='consulta.php' style='overflow-x:hidden; overflow-y:auto;'  frameborder='0' scrolling='yes' marginheight='0' marginwidth='0' width='100%' height='600px'></iframe>";
		setActiveTab('b5');
}

function mostrarCC() {
		var contenido=document.getElementById('contenido');
		contenido.innerHTML="<iframe src='comprar.php' style='overflow-x:hidden; overflow-y:auto;' frameborder='0' scrolling='yes' marginheight='0' marginwidth='0' width='100%' height='600px'></iframe>";
		setActiveTab('b6');
}

function mostrarCT() {
		var contenido=document.getElementById('contenido');
		contenido.innerHTML="<iframe src='contabilidad.php' style='overflow-x:hidden; overflow-y:auto;' frameborder='0' scrolling='yes' marginheight='0' marginwidth='0' width='100%' height='600px'></iframe>";
		setActiveTab('b7');
}

function mostrarMA() {
		var contenido=document.getElementById('contenido');
		contenido.innerHTML="<iframe src='mantenimiento.php' style='overflow-x:hidden; overflow-y:auto;' frameborder='0' scrolling='yes' marginheight='0' marginwidth='0' width='100%' height='600px'></iframe>";
		setActiveTab('b8');
}

function setActiveTab(activeId) {
	// Remove active class from all tabs
	var tabs = ['b1', 'b2', 'b3', 'b4', 'b5', 'b6', 'b7', 'b8'];
	for (var i = 0; i < tabs.length; i++) {
		var tab = document.getElementById(tabs[i]);
		if (tab) {
			tab.classList.remove('active');
		}
	}
	// Add active class to the selected tab
	var activeTab = document.getElementById(activeId);
	if (activeTab) {
		activeTab.classList.add('active');
	}
}
