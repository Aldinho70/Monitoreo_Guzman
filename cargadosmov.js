var unidadesCount4 = 0;
// Función para cargar y procesar el archivo JSON
function cargarJSON4() {
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
    }
    console.log('test');
    markers = [];
    $.ajax({
        url: "json_arrcargadosmov.json",
        dataType: "json",
        success: function (json4) {
            // Limpiar el contenido existente
            $("#contenedor4").empty();
            var unidadesCount4 = 0;
            $("#botonTotalcargadosmov").text("Unidades Cargadas en movimiento 0");
                        $("#tabla-jsoncargadosmov").empty();

            // Crear la tabla para los objetos del JSON
            var tabla4 = $("<table>");
            tabla4.addClass("tabla-jsoncargadosmov");
            tabla4.css("display", "none"); // Ocultar la tabla

            // Crear la cabecera de la tabla
            var cabecera4 = $("<tr>");
            cabecera4.append($("<th>").text("Unidad"));
            cabecera4.append($("<th>").text("Origen"));
            cabecera4.append($("<th>").text("Destino"));
            cabecera4.append($("<th>").text("Ultimo mensaje"));
            cabecera4.append($("<th>").text("Doble"));
            cabecera4.append($("<th>").text("Ultimo mensaje"));
            cabecera4.append($("<th>").text("Caja"));
            cabecera4.append($("<th>").text("Ultimo menssaje"));


            cabecera4.append($("<th>").text("Bitacora"));
            tabla4.append(cabecera4);

 // Recorrer los elementos del JSON
for (var i = 0; i < json4.length; i++) {
var elemento4 = json4[i];
var lng = elemento4.longitud;
var lat = elemento4.Latitud;
     var unidad = elemento4.Unidad;
// Crear una fila para cada objeto
var fila4 = $("<tr>");

// Agregar las celdas con los datos correspondientes
fila4.append($("<td>").text(elemento4.Unidad));
fila4.append($("<td>").text(elemento4.Origen));
fila4.append($("<td>").text(elemento4.Destino));
fila4.append($("<td>").text(elemento4.Diferencia_tiempo));

fila4.append($("<td>").text(elemento4.Doble));
fila4.append($("<td>").text(elemento4.Doble_Reportando));
fila4.append($("<td>").text(elemento4.Caja));
fila4.append($("<td>").text(elemento4.Caja_Reportando));


unidadesCount4++;

$("#botonTotalcargadosmov").text("Unidades Cargadas en movimiento (" + unidadesCount4 + ")");

                // Crear una variable y un botón de alternancia para cada objeto
                var toggleButton4 = $("<input>").attr({
                    type: "checkbox",
                    id: "toggle" + i
                }).on("click", createToggleButtonHandler4(lat, lng, elemento4.Unidad, elemento4.Campos));

                fila4.append($("<td>").append(toggleButton4));

                // Agregar la fila a la tabla
                tabla4.append(fila4);
            }
            // Agregar la tabla al contenedor
            $("#contenedor4").append(tabla4);

            // Llamar a la función nuevamente después de un tiempo para actualizar los datos
            setTimeout(cargarJSON4, 60000); // Actualizar cada 95 segundos (5000 ms)
        },
        error: function (xhr, status, error) {
            console.error("Error al cargar el archivo JSON");
        }
    });
}

// Función para manejar el clic en el botón de alternancia
function createToggleButtonHandler4(lat, lng, unidad, campos) {
    return function() {
        var index4 = parseInt($(this).attr("id").substr(6));

        if ($(this).is(":checked")) {
            markers[index4] = addMarker(lat, lng, unidad);

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
            markers[index4].setMap(null);
        }
    };
}

$(document).ready(function () {
    cargarJSON4();
});
