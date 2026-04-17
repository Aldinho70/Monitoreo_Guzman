var unidadesCount10 = 0;
// Función para cargar y procesar el archivo JSON
function cargarJSON10() {
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
    }
    markers = [];
    $.ajax({
        url: "json_arrvaciosdet.json",
        dataType: "json",
        success: function (json10) {
            // Limpiar el contenido existente
            $("#contenedor10").empty();
            var unidadesCount10 = 0;
            $("#botonTotalvaciosdet").text("Unidades Vacias Detenidas 0");
                        $("#tabla-jsonvaciosdet").empty();

            // Crear la tabla para los objetos del JSON
            var tabla10 = $("<table>");
            tabla10.addClass("tabla-jsonvaciosdet");
            tabla10.css("display", "none"); // Ocultar la tabla

            // Crear la cabecera de la tabla
            var cabecera10 = $("<tr>");
            cabecera10.append($("<th>").text("Unidad"));
            cabecera10.append($("<th>").text("Origen"));
            cabecera10.append($("<th>").text("Destino"));
            cabecera10.append($("<th>").text("Ultimo mensaje"));
            cabecera10.append($("<th>").text("Doble"));
            cabecera10.append($("<th>").text("Ultimo mensaje"));
            cabecera10.append($("<th>").text("Caja"));
            cabecera10.append($("<th>").text("Ultimo menssaje"));


            cabecera10.append($("<th>").text("Bitacora"));
            tabla10.append(cabecera10);

 // Recorrer los elementos del JSON
for (var i = 0; i < json10.length; i++) {
var elemento10 = json10[i];
var lng = elemento10.longitud;
var lat = elemento10.Latitud;
     var unidad = elemento10.Unidad;
// Crear una fila para cada objeto
var fila10 = $("<tr>");

// Agregar las celdas con los datos correspondientes
fila10.append($("<td>").text(elemento10.Unidad));
fila10.append($("<td>").text(elemento10.Origen));
fila10.append($("<td>").text(elemento10.Destino));
fila10.append($("<td>").text(elemento10.Diferencia_tiempo));

fila10.append($("<td>").text(elemento10.Doble));
fila10.append($("<td>").text(elemento10.Doble_Reportando));
fila10.append($("<td>").text(elemento10.Caja));
fila10.append($("<td>").text(elemento10.Caja_Reportando));




unidadesCount10++;

$("#botonTotalvaciosdet").text("Unidades Vacias Detenidas (" + unidadesCount10 + ")");

                // Crear una variable y un botón de alternancia para cada objeto
                var toggleButton10 = $("<input>").attr({
                    type: "checkbox",
                    id: "toggle" + i
                }).on("click", createToggleButtonHandler10(lat, lng, elemento10.Unidad, elemento10.Campos));

                fila10.append($("<td>").append(toggleButton10));

                // Agregar la fila a la tabla
                tabla10.append(fila10);
            }
            // Agregar la tabla al contenedor
            $("#contenedor10").append(tabla10);

            // Llamar a la función nuevamente después de un tiempo para actualizar los datos
            setTimeout(cargarJSON10, 60000); // Actualizar cada 105 segundos (5000 ms)
        },
        error: function (xhr, status, error) {
            console.error("Error al cargar el archivo JSON");
        }
    });
}

// Función para manejar el clic en el botón de alternancia
function createToggleButtonHandler10(lat, lng, unidad, campos) {
    return function() {
        var index10 = parseInt($(this).attr("id").substr(6));

        if ($(this).is(":checked")) {
            markers[index10] = addMarker(lat, lng, unidad);

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
            markers[index10].setMap(null);
        }
    };
}

$(document).ready(function () {
    cargarJSON10();
});
