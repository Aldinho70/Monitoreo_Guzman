var unidadesCount2 = 0;
// Función para cargar y procesar el archivo JSON
function cargarJSON2() {
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
    }
    markers = [];
    $.ajax({
        url: "json_arrcargadosdead.json",
        dataType: "json",
        success: function (json2) {
            // Limpiar el contenido existente
            $("#contenedor2").empty();
            var unidadesCount2 = 0;
            $("#botonTotalsinreportar").text("Cargados Sin Reportar 0");
$("#botonTotalsinreportar").css("background-color", "gray");
$("#botonTotalsinreportar").css("animation", "gray");
$("#botonTotalsinreportar").css("animation", "ballooning 0s infinite");



                        $("#tabla-jsonsinreportar").empty();

            // Crear la tabla para los objetos del JSON
            var tabla2 = $("<table>");
            tabla2.addClass("tabla-jsonsinreportar");
            tabla2.css("display", "none"); // Ocultar la tabla

            // Crear la cabecera de la tabla
            var cabecera2 = $("<tr>");
            cabecera2.append($("<th>").text("Unidad"));
            cabecera2.append($("<th>").text("Origen"));
            cabecera2.append($("<th>").text("Destino"));
            cabecera2.append($("<th>").text("Ultimo mensaje"));
            cabecera2.append($("<th>").text("Doble"));
            cabecera2.append($("<th>").text("Ultimo mensaje"));
            cabecera2.append($("<th>").text("Caja"));
            cabecera2.append($("<th>").text("Ultimo menssaje"));


            cabecera2.append($("<th>").text("Mostrar en mapa"));
            tabla2.append(cabecera2);

 // Recorrer los elementos del JSON
for (var i = 0; i < json2.length; i++) {
var elemento2 = json2[i];
var lng = elemento2.longitud;
var lat = elemento2.Latitud;
     var unidad = elemento2.Unidad;
// Crear una fila para cada objeto
var fila2 = $("<tr>");

// Agregar las celdas con los datos correspondientes
fila2.append($("<td>").text(elemento2.Unidad));
fila2.append($("<td>").text(elemento2.Origen));
fila2.append($("<td>").text(elemento2.Destino));
fila2.append($("<td>").text(elemento2.Diferencia_tiempo));

fila2.append($("<td>").text(elemento2.Doble));
fila2.append($("<td>").text(elemento2.Doble_Reportando));
fila2.append($("<td>").text(elemento2.Caja));
fila2.append($("<td>").text(elemento2.Caja_Reportando));




unidadesCount2++;

$("#botonTotalsinreportar").text("Cargados Sin Reportar (" + unidadesCount2 + ")");
$("#botonTotalsinreportar").css("background-color", "red");
$("#botonTotalsinreportar").css("animation", "ballooning 2s infinite");



                // Crear una variable y un botón de alternancia para cada objeto
                var toggleButton2 = $("<input>").attr({
                    type: "checkbox",
                    id: "toggle" + i
                }).on("click", createToggleButtonHandler2(lat, lng, elemento2.Unidad, elemento2.Campos));

                fila2.append($("<td>").append(toggleButton2));

                // Agregar la fila a la tabla
                tabla2.append(fila2);
            }
            // Agregar la tabla al contenedor
            $("#contenedor2").append(tabla2);

            // Llamar a la función nuevamente después de un tiempo para actualizar los datos
            setTimeout(cargarJSON2, 60000); // Actualizar cada 95 segundos (5000 ms)
        },
        error: function (xhr, status, error) {
            console.error("Error al cargar el archivo JSON");
        }
    });
}

// Función para manejar el clic en el botón de alternancia
function createToggleButtonHandler2(lat, lng, unidad, campos) {
    return function() {
        var index2 = parseInt($(this).attr("id").substr(6));

        if ($(this).is(":checked")) {
            markers[index2] = addMarker(lat, lng, unidad);

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
            markers[index2].setMap(null);
            camposContainer.empty();
        }
    };
}

$(document).ready(function () {
    cargarJSON2();
});
