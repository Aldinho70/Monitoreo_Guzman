var unidadesCount = 0;
// Función para cargar y procesar el archivo JSON
function cargarJSON() {
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
    }
    markers = [];
    $.ajax({
        url: "json_arrcargados.json",
        dataType: "json",
        success: function (json) {
            // Limpiar el contenido existente
            $("#contenedor").empty();
            var unidadesCount = 0;
            $("#botonTotalcargados").text("Unidades Cargadas 0");
            $("#tabla-jsoncargados").empty();

            // Crear la tabla para los objetos del JSON
            var tabla = $("<table>");
            tabla.addClass("tabla-jsoncargados");
            tabla.css("display", "none"); // Ocultar la tabla

            // Crear la cabecera de la tabla
            var cabecera = $("<tr>");
            cabecera.append($("<th>").text("Unidad"));
            cabecera.append($("<th>").text("Origen"));
            cabecera.append($("<th>").text("Destino"));
            cabecera.append($("<th>").text("Ultimo mensaje"));
            cabecera.append($("<th>").text("Doble"));
            cabecera.append($("<th>").text("Ultimo mensaje"));
            cabecera.append($("<th>").text("Caja"));
            cabecera.append($("<th>").text("Ultimo mensaje"));
            cabecera.append($("<th>").text("Temperatura"));
            cabecera.append($("<th>").text("Bitacora"));
            
            
                        tabla.append(cabecera);


// Recorrer los elementos del JSON
for (var i = 0; i < json.length; i++) {
var elemento = json[i];
var lng = elemento.longitud;
var lat = elemento.Latitud;
// Crear una fila para cada objeto
var fila = $("<tr>");

// Agregar las celdas con los datos correspondientes
fila.append($("<td>").text(elemento.Unidad));
fila.append($("<td>").text(elemento.Origen));
fila.append($("<td>").text(elemento.Destino));
fila.append($("<td>").text(elemento.Diferencia_tiempo));
fila.append($("<td>").text(elemento.Doble));
fila.append($("<td>").text(elemento.Doble_Reportando));
fila.append($("<td>").text(elemento.Caja));
fila.append($("<td>").text(elemento.Caja_Reportando));
fila.append($("<td>").text(elemento.Temperatura));


unidadesCount++;

$("#botonTotalcargados").text("Unidades Cargadas (" + unidadesCount + ")");



                // Crear una variable y un botón de alternancia para cada objeto
                var toggleButton = $("<input>").attr({
                    type: "checkbox",
                    id: "toggle" + i
                }).on("click", createToggleButtonHandler(lat, lng, elemento.Unidad, elemento.Campos));

                fila.append($("<td>").append(toggleButton));

                // Agregar la fila a la tabla
                tabla.append(fila);
            }
            // Agregar la tabla al contenedor
            $("#contenedor").append(tabla);

            // Llamar a la función nuevamente después de un tiempo para actualizar los datos
            setTimeout(cargarJSON, 60000); // Actualizar cada 95 segundos (5000 ms)
        },
        error: function (xhr, status, error) {
            console.error("Error al cargar el archivo JSON");
        }
    });
}

// Función para manejar el clic en el botón de alternancia
function createToggleButtonHandler(lat, lng, unidad, campos) {
    return function() {
        var index = parseInt($(this).attr("id").substr(6));

        if ($(this).is(":checked")) {
            markers[index] = addMarker(lat, lng, unidad);

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
            markers[index].setMap(null);
        }
    };
}

$(document).ready(function () {
    cargarJSON();
});
