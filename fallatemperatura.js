var unidadesCount15 = 0;

function cargarJSON15() {
    $.ajax({
        url: "json_arrfallatemp.json",
        dataType: "json",
        success: function (json15) {
            // Limpiar el contenido existente
            $("#contenedor15").empty();
            var unidadesCount15 = 0;
            $("#fallatemperatura").text("Falla de temperatura 0");

            // Crear la tabla para los objetos del JSON
            var tabla15 = $("<table>");
            tabla15.addClass("tabla-json_arrfallatemperatura");
            tabla15.css("display", "none"); // Ocultar la tabla

            // Crear la cabecera de la tabla
            var cabecera15 = $("<tr>");
            cabecera15.append($("<th>").text("Unidad"));
            cabecera15.append($("<th>").text("Ultimo Mensaje"));
            cabecera15.append($("<th>").text("Temperatura"));
            cabecera15.append($("<th>").text("Voltaje"));
            cabecera15.append($("<th>").text("Direccion"));
            cabecera15.append($("<th>").text("Mostrar en mapa"));

            tabla15.append(cabecera15);


            // Recorrer los elementos del JSON
            for (var i = 0; i < json15.length; i++) {
                var elemento15 = json15[i];
                var lng = elemento15.longitud;
                var lat = elemento15.Latitud;
                // Crear una fila para cada objeto
                var fila15 = $("<tr>");

                // Agregar las celdas con los datos correspondientes
                fila15.append($("<td>").text(elemento15.Unidad));
                fila15.append($("<td>").text(elemento15.Ultimo_mensaje));
                fila15.append($("<td>").text(elemento15.Temperatura));
                fila15.append($("<td>").text(elemento15.Voltaje));
                fila15.append($("<td>").text(elemento15.Direccion));






                unidadesCount15++;

                $("#fallatemperatura").text("Falla de temperatura (" + unidadesCount15 + ")");

                // Crear una variable y un botón de alternancia para cada objeto
                var toggleButton15 = $("<input>").attr({
                    type: "checkbox",
                    id: "toggle" + i
                }).on("change", createToggleButtonHandler15(lat, lng, elemento15.Unidad));

                fila15.append($("<td>").append(toggleButton15));

                // Agregar la fila a la tabla
                tabla15.append(fila15);
            }
            // Agregar la tabla al contenedor
            $("#contenedor15").append(tabla15);

            // Llamar a la función nuevamente después de un tiempo para actualizar los datos
            setTimeout(cargarJSON15, 60000); // Actualizar cada 95 segundos (5000 ms)
        },
        error: function (xhr, status, error) {
            console.error("Error al cargar el archivo JSON");
        }
    });
}

// Función para manejar el cambio de la alternancia del botón
function createToggleButtonHandler15(lat, lng, unidad) {
    return function () {
        var index15 = parseInt($(this).attr("id").substr(6));

        if ($(this).is(":checked")) {
            markers[index15] = addMarker(lat, lng, unidad);
        } else {
            markers[index15].setMap(null);
        }
    };
}
// Ejecutar la función al cargar la página
$(document).ready(function () {
    cargarJSON15();


});
