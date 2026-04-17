var unidadesCount14 = 0;

function cargarJSON14() {
    $.ajax({
        url: "json_arrdoblessinreportar.json",
        dataType: "json",
        success: function (json14) {
            // Limpiar el contenido existente
            $("#contenedor14").empty();
            var unidadesCount14 = 0;
            $("#doblessinreportar").text("Dobles sin reportar 0");

            // Crear la tabla para los objetos del JSON
            var tabla14 = $("<table>");
            tabla14.addClass("tabla-json_arrdoblessinreportar");
            tabla14.css("display", "none"); // Ocultar la tabla

            // Crear la cabecera de la tabla
            var cabecera14 = $("<tr>");
            cabecera14.append($("<th>").text("Unidad"));
            cabecera14.append($("<th>").text("Ultimo Mensaje"));
            cabecera14.append($("<th>").text("Voltaje"));
            cabecera14.append($("<th>").text("Bitacora"));

            tabla14.append(cabecera14);


            // Recorrer los elementos del JSON
            for (var i = 0; i < json14.length; i++) {
                var elemento14 = json14[i];
                var lng = elemento14.longitud;
                var lat = elemento14.Latitud;
                // Crear una fila para cada objeto
                var fila14 = $("<tr>");

                // Agregar las celdas con los datos correspondientes
                fila14.append($("<td>").text(elemento14.Unidad));
                fila14.append($("<td>").text(elemento14.Diferencia_tiempo));
                fila14.append($("<td>").text(elemento14.Voltaje));






                unidadesCount14++;

                $("#doblessinreportar").text("Dobles sin reportar (" + unidadesCount14 + ")");

                // Crear una variable y un botón de alternancia para cada objeto
                var toggleButton14 = $("<input>").attr({
                    type: "checkbox",
                    id: "toggle" + i
                }).on("change", createToggleButtonHandler14(lat, lng, elemento14.Unidad));

                fila14.append($("<td>").append(toggleButton14));

                // Agregar la fila a la tabla
                tabla14.append(fila14);
            }
            // Agregar la tabla al contenedor
            $("#contenedor14").append(tabla14);

            // Llamar a la función nuevamente después de un tiempo para actualizar los datos
            setTimeout(cargarJSON14, 60000); // Actualizar cada 95 segundos (5000 ms)
        },
        error: function (xhr, status, error) {
            console.error("Error al cargar el archivo JSON");
        }
    });
}

// Función para manejar el cambio de la alternancia del botón
function createToggleButtonHandler14(lat, lng, unidad) {
    return function () {
        var index14 = parseInt($(this).attr("id").substr(6));

        if ($(this).is(":checked")) {
            markers[index14] = addMarker(lat, lng, unidad);
        } else {
            markers[index14].setMap(null);
        }
    };
}
// Ejecutar la función al cargar la página
$(document).ready(function () {
    cargarJSON14();


});
