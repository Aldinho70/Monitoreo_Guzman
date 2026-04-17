var unidadesCount12 = 0;
// Función para cargar y procesar el archivo JSON

function cargarJSON12() {
        for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
    }
    markers = [];
    
    $.ajax({
        url: "json_arrcajas.json",
        dataType: "json",
        success: function (json12) {
            // Limpiar el contenido existente
            $("#contenedor12").empty();
            $("#tabla-jsoncajas").empty();
            var unidadesCount12 = 0;
            $("#botonTotalcajas").text("Cajas sin reportar 0");


            // Crear la tabla para los objetos del JSON
            var tabla12 = $("<table>");
            tabla12.addClass("tabla-jsoncajas");
            tabla12.css("display", "none"); // Ocultar la tabla

            // Crear la cabecera de la tabla
            var cabecera12 = $("<tr>");
            cabecera12.append($("<th>").text("Unidad"));
            cabecera12.append($("<th>").text("Ultimo mensaje"));
            cabecera12.append($("<th>").text("Voltaje"));

            cabecera12.append($("<th>").text("Bitacora"));
            tabla12.append(cabecera12);


// Recorrer los elementos del JSON
for (var i = 0; i < json12.length; i++) {
var elemento12 = json12[i];
var lng = elemento12.longitud;
var lat = elemento12.Latitud;
var temp = elemento12.Temperatura;
var online = elemento12.Online;
// Crear una fila para cada objeto
var fila12 = $("<tr>");

// Agregar las celdas con los datos correspondientes
fila12.append($("<td>").text(elemento12.Unidad));
fila12.append($("<td>").text(elemento12.Ultimo_mensaje));
fila12.append($("<td>").text(elemento12.Voltaje));





unidadesCount12++;

$("#botonTotalcajas").text("Cajas sin reportar (" + unidadesCount12 + ")");

// Crear una variable y un botón de alternancia para cada objeto
var toggleButton12 = $("<input>").attr({
type: "checkbox",
id: "toggle" + i
}).on("change", createToggleButtonHandler(lat, lng, elemento12.Unidad));

fila12.append($("<td>").append(toggleButton12));

// Agregar la fila a la tabla
tabla12.append(fila12);
}
            // Agregar la tabla al contenedor
            $("#contenedor12").append(tabla12);

            // Llamar a la función nuevamente después de un tiempo para actualizar los datos
            setTimeout(cargarJSON12, 60000); // Actualizar cada 95 segundos (5000 ms)
        },
        error: function (xhr, status, error) {
            console.error("Error al cargar el archivo JSON");
        }
    });
}

// Función para manejar el cambio de la alternancia del botón
function createToggleButtonHandler12(lat, lng, unidad) {
    return function() {
        var index12 = parseInt($(this).attr("id").substr(6));

        if ($(this).is(":checked")) {
            markers[index12] = addMarker(lat, lng, unidad);
        } else {
            markers[index12].setMap(null);
        }
    };
}

$(document).ready(function () {
    cargarJSON12();
});