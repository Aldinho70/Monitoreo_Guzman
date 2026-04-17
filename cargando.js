var unidadesCount5 = 0;
// Función para cargar y procesar el archivo JSON
function cargarJSON5() {
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
    }
    markers = [];
    $.ajax({
        url: "json_arrcargando.json",
        dataType: "json",
        success: function (json5) {
            // Limpiar el contenido existente
         $("#contenedor5").empty();
            var unidadesCount5 = 0;
            $("#botonTotalcargando").text("Unidades Cargando 0");
                                    $("#tabla-jsoncargando").empty();

            // Crear la tabla para los objetos del JSON
            var tabla5 = $("<table>");
            tabla5.addClass("tabla-jsoncargando");
            tabla5.css("display", "none"); // Ocultar la tabla

            // Crear la cabecera de la tabla
            var cabecera5 = $("<tr>");
            cabecera5.append($("<th>").text("Unidad"));
            cabecera5.append($("<th>").text("Origen"));
            cabecera5.append($("<th>").text("Destino"));
            cabecera5.append($("<th>").text("Bitacora"));
            tabla5.append(cabecera5);

            // Recorrer los elementos del JSON
            for (var i = 0; i < json5.length; i++) {
                var elemento5 = json5[i];
                var lng = elemento5.longitud;
                var lat = elemento5.Latitud;
                var unidad = elemento5.Unidad;

                // Crear una fila para cada objeto
                var fila5 = $("<tr>");

                // Agregar las celdas con los datos correspondientes
                fila5.append($("<td>").text(elemento5.Unidad));
                fila5.append($("<td>").text(elemento5.Origen));
                fila5.append($("<td>").text(elemento5.Destino));

                unidadesCount5++;

$("#botonTotalcargando").text("Unidades Cargando (" + unidadesCount5 + ")");


                // Crear una variable y un botón de alternancia para cada objeto
                var toggleButton5 = $("<input>").attr({
                    type: "checkbox",
                    id: "toggle" + i
                }).on("click", createToggleButtonHandler5(lat, lng, elemento5.Unidad, elemento5.Campos));

                fila5.append($("<td>").append(toggleButton5));

                // Agregar la fila a la tabla
                tabla5.append(fila5);
            }
            // Agregar la tabla al contenedor
            $("#contenedor5").append(tabla5);

            // Llamar a la función nuevamente después de un tiempo para actualizar los datos
            setTimeout(cargarJSON5, 60000); // Actualizar cada 95 segundos (5000 ms)
        },
        error: function (xhr, status, error) {
            console.error("Error al cargar el archivo JSON");
        }
    });
}

// Función para manejar el clic en el botón de alternancia
function createToggleButtonHandler5(lat, lng, unidad, campos) {
    return function() {
        var index5 = parseInt($(this).attr("id").substr(6));

        if ($(this).is(":checked")) {
            markers[index5] = addMarker(lat, lng, unidad);

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
            markers[index5].setMap(null);
        }
    };
}

$(document).ready(function () {
    cargarJSON5();
});
