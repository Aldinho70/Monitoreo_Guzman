var unidadesCount6 = 0;
// Función para cargar y procesar el archivo JSON
function cargarJSON6() {
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
    }
    markers = [];
    $.ajax({
        url: "json_arrdescargando.json",
        dataType: "json",
        success: function (json6) {
            // Limpiar el contenido existente
            $("#contenedor6").empty();
            var unidadesCount6 = 0;
            $("#botonTotaldescargando").text("Unidades Descargando 0");
                                                $("#tabla-jsondescargando").empty();

            // Crear la tabla para los objetos del JSON
            var tabla6 = $("<table>");
            tabla6.addClass("tabla-jsondescargando");
            tabla6.css("display", "none"); // Ocultar la tabla

            // Crear la cabecera de la tabla
            var cabecera6 = $("<tr>");
            cabecera6.append($("<th>").text("Unidad"));
            cabecera6.append($("<th>").text("Origen"));
            cabecera6.append($("<th>").text("Destino"));
            cabecera6.append($("<th>").text("Bitacora"));
            tabla6.append(cabecera6);

            // Recorrer los elementos del JSON
            for (var i = 0; i < json6.length; i++) {
                var elemento6 = json6[i];
                var lng = elemento6.longitud;
                var lat = elemento6.Latitud;
                var unidad = elemento6.Unidad;

                // Crear una fila para cada objeto
                var fila6 = $("<tr>");

                // Agregar las celdas con los datos correspondientes
                fila6.append($("<td>").text(elemento6.Unidad));
                fila6.append($("<td>").text(elemento6.Origen));
                fila6.append($("<td>").text(elemento6.Destino));


unidadesCount6++;

$("#botonTotaldescargando").text("Unidades Descargando (" + unidadesCount6 + ")");

                // Crear una variable y un botón de alternancia para cada objeto
                var toggleButton6 = $("<input>").attr({
                    type: "checkbox",
                    id: "toggle" + i
                }).on("click", createToggleButtonHandler6(lat, lng, elemento6.Unidad, elemento6.Campos));

                fila6.append($("<td>").append(toggleButton6));

                // Agregar la fila a la tabla
                tabla6.append(fila6);
            }
            // Agregar la tabla al contenedor
            $("#contenedor6").append(tabla6);

            // Llamar a la función nuevamente después de un tiempo para actualizar los datos
            setTimeout(cargarJSON6, 60000); // Actualizar cada 95 segundos (5000 ms)
        },
        error: function (xhr, status, error) {
            console.error("Error al cargar el archivo JSON");
        }
    });
}

// Función para manejar el clic en el botón de alternancia
function createToggleButtonHandler6(lat, lng, unidad, campos) {
    return function() {
        var index6 = parseInt($(this).attr("id").substr(6));

        if ($(this).is(":checked")) {
            markers[index6] = addMarker(lat, lng, unidad);

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
            markers[index6].setMap(null);
        }
    };
}

$(document).ready(function () {
    cargarJSON6();
});
