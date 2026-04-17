var unidadesCount16 = 0;

function cargarJSON16() {
    $.ajax({
        url: "json_arrcajasd.json",
        dataType: "json",
        success: function (json16) {
            // Limpiar el contenido existente
            $("#contenedor16").empty();
            var unidadesCount16 = 0;
            $("#botonTotalcajasd").text("Cajas dobles sin reportar 0");

            // Crear la tabla para los objetos del JSON
            var tabla16 = $("<table>");
            tabla16.addClass("tabla-jsoncajasd");
            tabla16.css("display", "none"); // Ocultar la tabla

            // Crear la cabecera de la tabla
            var cabecera16 = $("<tr>");
            cabecera16.append($("<th>").text("Unidad"));
            cabecera16.append($("<th>").text("Ultimo Mensaje"));
            cabecera16.append($("<th>").text("Voltaje"));
            cabecera16.append($("<th>").text("Bitacora"));

            tabla16.append(cabecera16);


            // Recorrer los elementos del JSON
            for (var i = 0; i < json16.length; i++) {
                var elemento16 = json16[i];
                var lng = elemento16.longitud;
                var lat = elemento16.Latitud;
                // Crear una fila para cada objeto
                var fila16 = $("<tr>");

                // Agregar las celdas con los datos correspondientes
                fila16.append($("<td>").text(elemento16.Unidad));
                fila16.append($("<td>").text(elemento16.Ultimo_mensaje));
                fila16.append($("<td>").text(elemento16.Voltaje));






                unidadesCount16++;

                $("#botonTotalcajasd").text("Cajas dobles sin reportar (" + unidadesCount16 + ")");

                // Crear una variable y un botón de alternancia para cada objeto
                var toggleButton16 = $("<input>").attr({
                    type: "checkbox",
                    id: "toggle" + i
                }).on("change", createToggleButtonHandler16(lat, lng, elemento16.Unidad));

                fila16.append($("<td>").append(toggleButton16));

                // Agregar la fila a la tabla
                tabla16.append(fila16);
            }
            // Agregar la tabla al contenedor
            $("#contenedor16").append(tabla16);

            // Llamar a la función nuevamente después de un tiempo para actualizar los datos
            setTimeout(cargarJSON16, 60000); // Actualizar cada 95 segundos (5000 ms)
        },
        error: function (xhr, status, error) {
            console.error("Error al cargar el archivo JSON");
        }
    });
}

// Función para manejar el cambio de la alternancia del botón
function createToggleButtonHandler16(lat, lng, unidad) {
    return function () {
        var index16 = parseInt($(this).attr("id").substr(6));

        if ($(this).is(":checked")) {
            markers[index16] = addMarker(lat, lng, unidad);
        } else {
            markers[index16].setMap(null);
        }
    };
}
// Ejecutar la función al cargar la página
$(document).ready(function () {
    cargarJSON16();


});
