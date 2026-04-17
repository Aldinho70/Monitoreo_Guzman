var unidadesCount8 = 0;
// Función para cargar y procesar el archivo JSON
function cargarJSON8() {
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
    }
    markers = [];
    $.ajax({
        url: "json_arrsinstatus.json",
        dataType: "json",
        success: function (json8) {
            // Limpiar el contenido existente
            $("#contenedor8").empty();
            var unidadesCount8 = 0;
            $("#botonTotalsinstatus").text("Unidades Sin Status 0");
                        $("#botonTotalsinstatus").css("background-color", "gray");
                        $("#botonTotalsinstatus").css("animation", "ballooning 0s infinite");


            $("#tabla-jsonsinstatus").empty();

            // Crear la tabla para los objetos del JSON
            var tabla8 = $("<table>");
            tabla8.addClass("tabla-jsonsinstatus");
            tabla8.css("display", "none"); // Ocultar la tabla

                      // Crear la cabecera de la tabla
            var cabecera8 = $("<tr>");
            cabecera8.append($("<th>").text("Unidad"));
            cabecera8.append($("<th>").text("Origen"));
            cabecera8.append($("<th>").text("Destino"));
            cabecera8.append($("<th>").text("Ultimo mensaje"));
            cabecera8.append($("<th>").text("Doble"));
            cabecera8.append($("<th>").text("Ultimo mensaje"));
            cabecera8.append($("<th>").text("Caja"));
            cabecera8.append($("<th>").text("Ultimo menssaje"));


            cabecera8.append($("<th>").text("Bitacora"));
            tabla8.append(cabecera8);

 // Recorrer los elementos del JSON
for (var i = 0; i < json8.length; i++) {
var elemento8 = json8[i];
var lng = elemento8.longitud;
var lat = elemento8.Latitud;
     var unidad = elemento8.Unidad;
// Crear una fila para cada objeto
var fila8 = $("<tr>");

// Agregar las celdas con los datos correspondientes
fila8.append($("<td>").text(elemento8.Unidad));
fila8.append($("<td>").text(elemento8.Origen));
fila8.append($("<td>").text(elemento8.Destino));
fila8.append($("<td>").text(elemento8.Diferencia_tiempo));

fila8.append($("<td>").text(elemento8.Doble));
fila8.append($("<td>").text(elemento8.Doble_Reportando));
fila8.append($("<td>").text(elemento8.Caja));
fila8.append($("<td>").text(elemento8.Caja_Reportando));

                unidadesCount8++;

$("#botonTotalsinstatus").text("Unidades Sin Status (" + (unidadesCount8 === 0 ? "0" : unidadesCount8) + ")");
                        $("#botonTotalsinstatus").css("background-color", "red");
                        $("#botonTotalsinstatus").css("animation", "ballooning 2s infinite");


                // Crear una variable y un botón de alternancia para cada objeto
                var toggleButton8 = $("<input>").attr({
                    type: "checkbox",
                    id: "toggle" + i
                }).on("click", createToggleButtonHandler8(lat, lng, elemento8.Unidad, elemento8.Campos));

                fila8.append($("<td>").append(toggleButton8));

                // Agregar la fila a la tabla
                tabla8.append(fila8);
            }
            // Agregar la tabla al contenedor
            $("#contenedor8").append(tabla8);

            // Llamar a la función nuevamente después de un tiempo para actualizar los datos
            setTimeout(cargarJSON8, 60000); // Actualizar cada 95 segundos (5000 ms)
        },
        error: function (xhr, status, error) {
            console.error("Error al cargar el archivo JSON");
        }
    });
}

// Función para manejar el clic en el botón de alternancia
function createToggleButtonHandler8(lat, lng, unidad, campos) {
    return function() {
        var index8 = parseInt($(this).attr("id").substr(6));

        if ($(this).is(":checked")) {
            markers[index8] = addMarker(lat, lng, unidad);

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
            markers[index8].setMap(null);
        }
    };
}

$(document).ready(function () {
    cargarJSON8();
});
