var unidadesCount7 = 0;
// Función para cargar y procesar el archivo JSON
function cargarJSON7() {
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
    }
    markers = [];
    $.ajax({
        url: "json_arrdesvioruta.json",
        dataType: "json",
        success: function (json7) {
            // Limpiar el contenido existente
            $("#contenedor7").empty();
            var unidadesCount7 = 0;
            $("#botonTotaldesvio").text("Unidades Cargadas Desviadas 0");
            $("#botonTotaldesvio").css("background-color", "gray");
            $("#botonTotaldesvio").css("animation", "ballooning 0s infinite");


                        $("#tabla-jsondesvio").empty();

            // Crear la tabla para los objetos del JSON
            var tabla7 = $("<table>");
            tabla7.addClass("tabla-jsondesvio");
            tabla7.css("display", "none"); // Ocultar la tabla

            // Crear la cabecera de la tabla
            var cabecera7 = $("<tr>");
            cabecera7.append($("<th>").text("Unidad"));
            cabecera7.append($("<th>").text("Origen"));
            cabecera7.append($("<th>").text("Destino"));
            cabecera7.append($("<th>").text("Ultimo mensaje"));
            cabecera7.append($("<th>").text("Doble"));
            cabecera7.append($("<th>").text("Ultimo mensaje"));
            cabecera7.append($("<th>").text("Caja"));
            cabecera7.append($("<th>").text("Ultimo menssaje"));


            cabecera7.append($("<th>").text("Bitacora"));
            tabla7.append(cabecera7);



 // Recorrer los elementos del JSON
for (var i = 0; i < json7.length; i++) {
var elemento7 = json7[i];
var lng = elemento7.longitud;
var lat = elemento7.Latitud;
     var unidad = elemento7.Unidad;
// Crear una fila para cada objeto
var fila7 = $("<tr>");

// Agregar las celdas con los datos correspondientes
fila7.append($("<td>").text(elemento7.Unidad));
fila7.append($("<td>").text(elemento7.Origen));
fila7.append($("<td>").text(elemento7.Destino));
fila7.append($("<td>").text(elemento7.Diferencia_tiempo));

fila7.append($("<td>").text(elemento7.Doble));
fila7.append($("<td>").text(elemento7.Doble_Reportando));
fila7.append($("<td>").text(elemento7.Caja));
fila7.append($("<td>").text(elemento7.Caja_Reportando));

unidadesCount7++;

$("#botonTotaldesvio").text("Unidades Cargadas Desviadas (" + unidadesCount7 + ")");
            $("#botonTotaldesvio").css("background-color", "red");
            $("#botonTotaldesvio").css("animation", "ballooning 2s infinite");

            


                // Crear una variable y un botón de alternancia para cada objeto
                var toggleButton7 = $("<input>").attr({
                    type: "checkbox",
                    id: "toggle" + i
                }).on("click", createToggleButtonHandler7(lat, lng, elemento7.Unidad, elemento7.Campos));

                fila7.append($("<td>").append(toggleButton7));

                // Agregar la fila a la tabla
                tabla7.append(fila7);
            }
            // Agregar la tabla al contenedor
            $("#contenedor7").append(tabla7);

            // Llamar a la función nuevamente después de un tiempo para actualizar los datos
            setTimeout(cargarJSON7, 60000); // Actualizar cada 95 segundos (5000 ms)
        },
        error: function (xhr, status, error) {
            console.error("Error al cargar el archivo JSON");
        }
    });
}

// Función para manejar el clic en el botón de alternancia
function createToggleButtonHandler7(lat, lng, unidad, campos) {
    return function() {
        var index7 = parseInt($(this).attr("id").substr(6));

        if ($(this).is(":checked")) {
            markers[index7] = addMarker(lat, lng, unidad);

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
            markers[index7].setMap(null);
        }
    };
}

$(document).ready(function () {
    cargarJSON7();
});
