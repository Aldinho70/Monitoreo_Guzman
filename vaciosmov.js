var unidadesCount11 = 0;
// Función para cargar y procesar el archivo JSON
function cargarJSON11() {
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
    }
    markers = [];
    $.ajax({
        url: "json_arrvaciosmov.json",
        dataType: "json",
        success: function (json11) {
            // Limpiar el contenido existente
            $("#contenedor11").empty();
            var unidadesCount11 = 0;
            $("#botonTotalvaciosmov").text("Unidades Vacias Movimiento 0");
                        $("#tabla-jsonvaciosmov").empty();

            // Crear la tabla para los objetos del JSON
            var tabla11 = $("<table>");
            tabla11.addClass("tabla-jsonvaciosmov");
            tabla11.css("display", "none"); // Ocultar la tabla

            // Crear la cabecera de la tabla
            var cabecera11 = $("<tr>");
            cabecera11.append($("<th>").text("Unidad"));
            cabecera11.append($("<th>").text("Origen"));
            cabecera11.append($("<th>").text("Destino"));
            cabecera11.append($("<th>").text("Ultimo mensaje"));
            cabecera11.append($("<th>").text("Doble"));
            cabecera11.append($("<th>").text("Ultimo mensaje"));
            cabecera11.append($("<th>").text("Caja"));
            cabecera11.append($("<th>").text("Ultimo menssaje"));


            cabecera11.append($("<th>").text("Bitacora"));
            tabla11.append(cabecera11);

 // Recorrer los elementos del JSON
for (var i = 0; i < json11.length; i++) {
var elemento11 = json11[i];
var lng = elemento11.longitud;
var lat = elemento11.Latitud;
     var unidad = elemento11.Unidad;
// Crear una fila para cada objeto
var fila11 = $("<tr>");

// Agregar las celdas con los datos correspondientes
fila11.append($("<td>").text(elemento11.Unidad));
fila11.append($("<td>").text(elemento11.Origen));
fila11.append($("<td>").text(elemento11.Destino));
fila11.append($("<td>").text(elemento11.Diferencia_tiempo));

fila11.append($("<td>").text(elemento11.Doble));
fila11.append($("<td>").text(elemento11.Doble_Reportando));
fila11.append($("<td>").text(elemento11.Caja));
fila11.append($("<td>").text(elemento11.Caja_Reportando));




unidadesCount11++;

$("#botonTotalvaciosmov").text("Unidades Vacias Movimiento (" + unidadesCount11 + ")");

                // Crear una variable y un botón de alternancia para cada objeto
                var toggleButton11 = $("<input>").attr({
                    type: "checkbox",
                    id: "toggle" + i
                }).on("click", createToggleButtonHandler11(lat, lng, elemento11.Unidad, elemento11.Campos));

                fila11.append($("<td>").append(toggleButton11));

                // Agregar la fila a la tabla
                tabla11.append(fila11);
            }
            // Agregar la tabla al contenedor
            $("#contenedor11").append(tabla11);

            // Llamar a la función nuevamente después de un tiempo para actualizar los datos
            setTimeout(cargarJSON11, 60000); // Actualizar cada 115 segundos (5000 ms)
        },
        error: function (xhr, status, error) {
            console.error("Error al cargar el archivo JSON");
        }
    });
}

// Función para manejar el clic en el botón de alternancia
function createToggleButtonHandler11(lat, lng, unidad, campos) {
    return function() {
        var index11 = parseInt($(this).attr("id").substr(6));

        if ($(this).is(":checked")) {
            markers[index11] = addMarker(lat, lng, unidad);

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
            markers[index11].setMap(null);
        }
    };
}

$(document).ready(function () {
    cargarJSON11();
});
