/* Funciones para trabajar con el administrador de cada categoria 
   y la subida de ficheros y el manejo de su barra de progreso.
*/

function uploadFile(){

	var name = $("#nombre").val();
	var desc = $("#descripcion").val();
	var tipo = $("input:checked").val();
	var formdata = new FormData();
	
	// Comprueba si se ha cargado un archivo o se mantiene el mismo.
	if ($("#doc")[0].files[0]) {
		var file = $("#doc")[0].files[0];
		// Comprueba si es un nuevo PDF a insertar para borrar el anterior y si estamos en modo edicion
		if ($("#id_edit").val() != null) {
			if (file.name != $("#previous_doc").val())
				formdata.append("del_previous", $("#previous_doc").val());
		}
		
		formdata.append("doc", file);
	} else {
		var tmp = $("#previous_doc").val();
		formdata.append("previous_doc", tmp);
	}
	
	formdata.append("nombre", name);
	formdata.append("descripcion", desc);
	formdata.append("type", tipo);
	
	// Modo edicion o Agregando nuevo 
	if ($("#id_edit").val() != null) {
		$("#edit").val(1);
		formdata.append("id_edit", $("#id_edit").val());
	} else
		$("#add").val(1);

	var ajax = new XMLHttpRequest();
	ajax.upload.addEventListener("progress", progressHandler, false);
	ajax.addEventListener("load", completeHandler, false);
	ajax.addEventListener("error", errorHandler, false);
	ajax.addEventListener("abort", abortHandler, false);
	ajax.open("POST", "/concejo/admin/upload.php");
	ajax.send(formdata);
}

function uploadImage(){
	$("#add").val(1);
	var name = $("#nombre").val();
	var desc = $("#descripcion").val();
	
	var formdata = new FormData();
	
	var file = $("#foto")[0].files[0];
	formdata.append("foto", file);
	formdata.append("nombre", name);
	formdata.append("descripcion", desc);
		
	var ajax = new XMLHttpRequest();
	ajax.upload.addEventListener("progress", progressHandler, false);
	ajax.addEventListener("load", completeHandler, false);
	ajax.addEventListener("error", errorHandler, false);
	ajax.addEventListener("abort", abortHandler, false);	
	ajax.open("POST", "/concejo/admin/picUpload.php");
	ajax.send(formdata);
}

function uploadNews(){

	var name = $("#titulo").val();
	var desc = $("#descripcion").val();
	var art = $("#articulo").val();
	var date = $("#dateField").val();
	
	var formdata = new FormData();

	// Mantiene la misma imagen o la piensas cambiar.
	if ($("#foto")[0].files[0]) {
		var file = $("#foto")[0].files[0];
		formdata.append("foto", file);
	} else {
		if ($("#previous").val() != null) {
			var tmp = $("#previous").val();
			formdata.append("previous", tmp);
		} else {
			formdata.append("previous", "no.image.png");
			$("#error").val("AVISO. No se cargo una imagen para el articulo.");			
		}
	}
	
	formdata.append("titulo", name);
	formdata.append("descripcion", desc);
	formdata.append("articulo", art);
	formdata.append("fecha", date);

	// Modo edicion o Agregando nuevo 
	if ($("#id_edit").val() != null) {
		$("#edit").val(1);
		formdata.append("id_edit", $("#id_edit").val());
	} else
		$("#add").val(1);

	var ajax = new XMLHttpRequest();
	ajax.upload.addEventListener("progress", progressHandler, false);
	ajax.addEventListener("load", completeHandler, false);
	ajax.addEventListener("error", errorHandler, false);
	ajax.addEventListener("abort", abortHandler, false);	
	ajax.open("POST", "/concejo/admin/artUpload.php");
	ajax.send(formdata);

}

function uploadSession(){
	var sesion = $("#sesion").val();
	var day = $( "#day option:selected" ).text();
	var month = $( "#month option:selected" ).text();
	var year = $( "#year option:selected" ).text();
	
	var formdata = new FormData();

	formdata.append("year", year);
	formdata.append("month", month);
	formdata.append("day", day);
	formdata.append("sesion", sesion);

	$("#add").val(1);

	var ajax = new XMLHttpRequest();
	ajax.upload.addEventListener("progress", progressHandler, false);
	ajax.addEventListener("load", completeHandler, false);
	ajax.addEventListener("error", errorHandler, false);
	ajax.addEventListener("abort", abortHandler, false);	
	ajax.open("POST", "/concejo/admin/sesUpload.php");
	ajax.send(formdata);
}

function uploadVote(arg0){

	var formdata = new FormData();

	if(arg0 == 1) {
		var opcion = $("#opcion").val();
		$("#add").val(1);
	
		formdata.append("opcion", opcion);
	} else {
		var encuesta = $("#encuesta").val();
		$("#edit").val(1);

		formdata.append("encuesta", encuesta);
	}

	formdata.append("opt", arg0);

	var ajax = new XMLHttpRequest();
	ajax.upload.addEventListener("progress", progressHandler, false);
	ajax.addEventListener("load", completeHandler, false);
	ajax.addEventListener("error", errorHandler, false);
	ajax.addEventListener("abort", abortHandler, false);	
	ajax.open("POST", "/concejo/admin/voteUpload.php");
	ajax.send(formdata);
}

function uploadBio(){

	var name = $("#nombre").val();
	var bio = $("#biografia").val();
	
	var formdata = new FormData();

	// Mantiene la misma imagen o la piensas cambiar.
	if ($("#foto")[0].files[0]) {
		var file = $("#foto")[0].files[0];
		formdata.append("foto", file);
	} else {
		var tmp = $("#previous").val();
		formdata.append("previous", tmp);
	}
	
	formdata.append("nombre", name);
	formdata.append("biografia", bio);

	// Modo edicion o Agregando nuevo 
	if ($("#id_edit").val() != null) {
		$("#edit").val(1);
		formdata.append("id_edit", $("#id_edit").val());
	} else
		$("#add").val(1);

	var ajax = new XMLHttpRequest();
	ajax.upload.addEventListener("progress", progressHandler, false);
	ajax.addEventListener("load", completeHandler, false);
	ajax.addEventListener("error", errorHandler, false);
	ajax.addEventListener("abort", abortHandler, false);	
	ajax.open("POST", "/concejo/admin/bioUpload.php");
	ajax.send(formdata);

}

function uploadDir() {
	$("#edit").val(1);
	var name = $("#nombre").val();
	var cargo = $("#cargo").val();
	var formdata = new FormData();

	// Mantiene la misma imagen o la piensas cambiar.
	if ($("#foto")[0].files[0]) {
		var file = $("#foto")[0].files[0];
		formdata.append("foto", file);
	} else {
		var tmp = $("#previous").val();
		formdata.append("previous", tmp);
	}

	formdata.append("nombre", name);
	formdata.append("cargo", cargo);
	formdata.append("id_edit", $("#id_edit").val());

	var ajax = new XMLHttpRequest();
	ajax.upload.addEventListener("progress", progressHandler, false);
	ajax.addEventListener("load", completeHandler, false);
	ajax.addEventListener("error", errorHandler, false);
	ajax.addEventListener("abort", abortHandler, false);	
	ajax.open("POST", "/concejo/admin/dirUpload.php");
	ajax.send(formdata);

}

function logout() {	
	$.ajax({
		url: "/concejo/ingresar.php",
		method: "POST",
		data: { salir: true },
		dataType: "html",
		success: function(salir){
			location.reload();
		}
	});

}

function deleteID(delID) {
	$("#dele").val(1);
	var formdata = new FormData();
	formdata.append("dele", delID);
	var upload = $("#upload").val();

	var ajax = new XMLHttpRequest();
	ajax.upload.addEventListener("progress", progressHandler, false);
	ajax.addEventListener("load", completeHandler, false);
	ajax.addEventListener("error", errorHandler, false);
	ajax.addEventListener("abort", abortHandler, false);
	ajax.open("POST", upload );
	ajax.send(formdata);

}

function uploadVideo() {
	$("#add").val(1);
	var name = $("#nombre").val();
	var desc = $("#descripcion").val();
	var file = $("#videoFile")[0].files[0];
	
	var formdata = new FormData();	
	formdata.append("video", file);
	formdata.append("nombre", name);
	formdata.append("descripcion", desc);

	var ajax = new XMLHttpRequest();
	ajax.upload.addEventListener("progress", progressHandler, false);
	ajax.addEventListener("load", completeHandler, false);
	ajax.addEventListener("error", errorHandler, false);
	ajax.addEventListener("abort", abortHandler, false);	
	ajax.open("POST", "/concejo/admin/vidUpload.php");
	ajax.send(formdata);
}

function progressHandler(event){
	$("#progress_bar").css("display", "block");
	var percent = (event.loaded / event.total) * 100;
	$("#progress_bar").val(Math.round(percent));
	$("#status").html(Math.round(percent)+"% Cargado... Por favor espere");
}

function completeHandler(event){
	var output = $.trim(event.target.responseText);
	$("#status").html(output);
	$("#progress_bar").val(0);
	$("#form").submit();
}

function errorHandler(event){
	$("#status").html("Ocurrio un error durante la subida del archivo.");
}

function abortHandler(event){
	$("#status").html("Abortado!.");
}

/* Funcion para trabajar con el calendario y hacerlo mas dinamico.
*/

function calendario() {

	var mes = $("#months").val();

	var jqxhr = $.ajax({
		url: "/concejo/calendary.php",
		method: "POST",
		data: { mes: mes },
		dataType: "html",
		success: function(msg){
			$("#calendarioTable").html(msg);
		}
	});

}

function messaging(operation, msgId) {

	var page = $("#pag").val();
	if (operation == 'NEXT') {
		page = parseInt(page) + 1;
		$("#pag").val(page);
	}
	if (operation == 'PREV') {
		page = parseInt(page) - 1;
		$("#pag").val(page);
	}

	var jqxhr = $.ajax({
		url: "/concejo/messaging.php",
		method: "POST",
		data: { op: operation, id: msgId, pag: page },
		dataType: "html",
		success: function(msg){
			$("#mail").html(msg);
		}
	});
}

function triggerModal(path) {
	// Check in the path what kind of modal we gonna use!
	var parse = path.split("/");
	var type = parse[2];

	if (type == "galeria") {
		var gallery = "<img id='gallery' src='"+path+"' class='image-responsive' />";
		$(".modal-body").html(gallery);
		$("#myModal").modal();
	} else {
		var video = "<video id='video' controls autoplay src='"+path+"' ></video>";
		$(".modal-body").html(video);
		$("#myModal").modal();
	}
}