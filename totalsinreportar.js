var unidadesCount13 = 0;
// Función para cargar y procesar el archivo JSON
function cargarJSON13() {
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
    }
    markers = [];
    $.ajax({
        url: "json_arrtractossinreportar.json",
        dataType: "json",
        success: function (json13) {
            // Limpiar el contenido existente
     $("#contenedor13").empty();
            var unidadesCount13 = 0;
            $("#totalsinreportar").text("Principales sin reportar 0");


            // Crear la tabla para los objetos del JSON
            var tabla13 = $("<table>");
            tabla13.addClass("tabla-json_arrtractossinreportar");
            tabla13.css("display", "none"); // Ocultar la tabla

            // Crear la cabecera de la tabla
            var cabecera13 = $("<tr>");
            cabecera13.append($("<th>").text("Unidad"));
            cabecera13.append($("<th>").text("Ultimo Mensaje"));
            cabecera13.append($("<th>").text("Origen"));
            cabecera13.append($("<th>").text("Destino"));
            
            cabecera13.append($("<th>").text("Voltaje"));

            

            
            cabecera13.append($("<th>").text("Bitacora"));

            tabla13.append(cabecera13);


            // Recorrer los elementos del JSON
            for (var i = 0; i < json13.length; i++) {
                var elemento13 = json13[i];
                var lng = elemento13.longitud;
                var lat = elemento13.Latitud;
                // Crear una fila para cada objeto
                var fila13 = $("<tr>");

                // Agregar las celdas con los datos correspondientes
                fila13.append($("<td>").text(elemento13.Unidad));
                fila13.append($("<td>").text(elemento13.Diferencia_tiempo));
                fila13.append($("<td>").text(elemento13.Origen));
                fila13.append($("<td>").text(elemento13.Destino));
                
                fila13.append($("<td>").text(elemento13.Voltaje));






                unidadesCount13++;

                $("#totalsinreportar").text("Principales sin reportar (" + unidadesCount13 + ")");


                // Crear una variable y un botón de alternancia para cada objeto
                var toggleButton13 = $("<input>").attr({
                    type: "checkbox",
                    id: "toggle" + i
                }).on("click", createToggleButtonHandler13(lat, lng, elemento13.Unidad, elemento13.Campos));

                fila13.append($("<td>").append(toggleButton13));

                // Agregar la fila a la tabla
                tabla13.append(fila13);
            }
            // Agregar la tabla al contenedor
            $("#contenedor13").append(tabla13);

            // Llamar a la función nuevamente después de un tiempo para actualizar los datos
            setTimeout(cargarJSON13, 60000); // Actualizar cada 913 segundos (13000 ms)
        },
        error: function (xhr, status, error) {
            console.error("Error al cargar el archivo JSON");
        }
    });
}

// Función para manejar el clic en el botón de alternancia
function createToggleButtonHandler13(lat, lng, unidad, campos) {
    return function() {
        var index13 = parseInt($(this).attr("id").substr(6));

        if ($(this).is(":checked")) {
            markers[index13] = addMarker(lat, lng, unidad);

            // Mostrar la información adicional en el contenedor #camposcontainer
            var camposContainer = $("#camposcontainer");
            camposContainer.empty();

            // Crear una lista para mostrar los campos
            var camposList = $("<ul>");

            // Recorrer los campos y agregarlos a la lista
            for (var i = 0; i < campos.length; i++) {
                var campo = campos[i];
                var campounidad = $("<li>").text("Unidad: " + unidad);
                var campoItem = $("<li>").text(campo.Campo + ": " + campo.Valor + " - se modificó: " + campo.Ult_Modificacion);
               
                camposList.append(campoItem);
            }
            camposContainer.append(campounidad);
            camposContainer.append(camposList);
        } else {
            markers[index13].setMap(null);
        }
    };
}

$(document).ready(function () {
    cargarJSON13();
});
