
function ajax_connect() {

	if (window.XMLHttpRequest) {
		xmlhttp=new XMLHttpRequest();
	}
	else {
		alert("El navegador que estas usando esta obsoleto. Podes ver la version antigua de esta pagina, pero es extremadamente recomendable por tu propia seguridad que cambies de navegador web");
	}
	return xmlhttp;
}
function ajax(method,page,nowait,id,callback) {
	var contenedor=document.getElementById(id);
	var xmlhttp=ajax_connect();	

	if (xmlhttp!=null) {
		var url;
		var data=null;
		if (method == "GET") url=page;
		else {
			var regex=new RegExp("([^\?]+)\\?(.+)");
			var result=regex.exec(page);
			if (result==null) {
				url=page;
				method="GET";
			}
			else {
				url=result[1];
				data=result[2];
			}
		}

		if (contenedor!=null) contenedor.innerHTML="<div> Cargando...</div>"; 
		xmlhttp.open(method,url,nowait);
		if (nowait==true) 
   		    xmlhttp.onreadystatechange=function () {
			if (xmlhttp.readyState==4) {
				if (callback!=null) callback(contenedor,xmlhttp.responseText);
				else
				if (id=="") {
					document.write(xmlhttp.responseText);
					document.close();
				}
				else	if (contenedor!=null) contenedor.innerHTML=xmlhttp.responseText; 
			}
		   } 
		xmlhttp.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		xmlhttp.send(data);
		if (nowait==false) {
			if (callback!=null) callback(contenedor,xmlhttp.responseText);
			else
			if (id=="") {
					document.write(xmlhttp.responseText);
					document.close();
			}
			else	if (contenedor!=null) contenedor.innerHTML=xmlhttp.responseText;
		}
	}
}

