var unidadesCount9 = 0;
// Función para cargar y procesar el archivo JSON
function cargarJSON9() {
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
    }
    markers = [];
    $.ajax({
        url: "json_arrvacios.json",
        dataType: "json",
        success: function (json9) {
            // Limpiar el contenido existente
            $("#contenedor9").empty();
            var unidadesCount9 = 0;
            $("#botonTotalvacios").text("Unidades Vacias 0");
                        $("#tabla-jsonvacios").empty();

            // Crear la tabla para los objetos del JSON
            var tabla9 = $("<table>");
            tabla9.addClass("tabla-jsonvacios");
            tabla9.css("display", "none"); // Ocultar la tabla

            // Crear la cabecera de la tabla
            var cabecera9 = $("<tr>");
            cabecera9.append($("<th>").text("Unidad"));
            cabecera9.append($("<th>").text("Origen"));
            cabecera9.append($("<th>").text("Destino"));
            cabecera9.append($("<th>").text("Ultimo mensaje"));
            cabecera9.append($("<th>").text("Doble"));
            cabecera9.append($("<th>").text("Ultimo mensaje"));
            cabecera9.append($("<th>").text("Caja"));
            cabecera9.append($("<th>").text("Ultimo menssaje"));


            cabecera9.append($("<th>").text("Mostrar en mapa"));
            tabla9.append(cabecera9);

 // Recorrer los elementos del JSON
for (var i = 0; i < json9.length; i++) {
var elemento9 = json9[i];
var lng = elemento9.longitud;
var lat = elemento9.Latitud;
     var unidad = elemento9.Unidad;
// Crear una fila para cada objeto
var fila9 = $("<tr>");

// Agregar las celdas con los datos correspondientes
fila9.append($("<td>").text(elemento9.Unidad));
fila9.append($("<td>").text(elemento9.Origen));
fila9.append($("<td>").text(elemento9.Destino));
fila9.append($("<td>").text(elemento9.Diferencia_tiempo));

fila9.append($("<td>").text(elemento9.Doble));
fila9.append($("<td>").text(elemento9.Doble_Reportando));
fila9.append($("<td>").text(elemento9.Caja));
fila9.append($("<td>").text(elemento9.Caja_Reportando));




unidadesCount9++;

$("#botonTotalvacios").text("Unidades Vacias (" + unidadesCount9 + ")");

                // Crear una variable y un botón de alternancia para cada objeto
                var toggleButton9 = $("<input>").attr({
                    type: "checkbox",
                    id: "toggle" + i
                }).on("click", createToggleButtonHandler9(lat, lng, elemento9.Unidad, elemento9.Campos));

                fila9.append($("<td>").append(toggleButton9));

                // Agregar la fila a la tabla
                tabla9.append(fila9);
            }
            // Agregar la tabla al contenedor
            $("#contenedor9").append(tabla9);

            // Llamar a la función nuevamente después de un tiempo para actualizar los datos
            setTimeout(cargarJSON9, 60000); // Actualizar cada 95 segundos (5000 ms)
        },
        error: function (xhr, status, error) {
            console.error("Error al cargar el archivo JSON");
        }
    });
}

// Función para manejar el clic en el botón de alternancia
function createToggleButtonHandler9(lat, lng, unidad, campos) {
    return function() {
        var index9 = parseInt($(this).attr("id").substr(6));

        if ($(this).is(":checked")) {
            markers[index9] = addMarker(lat, lng, unidad);

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
            markers[index9].setMap(null);
        }
    };
}

$(document).ready(function () {
    cargarJSON9();
});
