var unidadesCount3 = 0;




// Función para cargar y procesar el archivo JSON
function cargarJSON3() {
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
    }
    markers = [];
    $.ajax({
        url: "json_arrcargadosdet.json",
        dataType: "json",
        success: function (json3) {
     

            
            // Limpiar el contenido existente
            $("#contenedor3").empty();
            var unidadesCount3 = 0;
            $("#botonTotalcargadosdet").text("Unidades Cargadas Detenidas 0");
            $("#tabla-jsoncargadosdetenidos tablesorter").empty();

            // Crear la tabla para los objetos del JSON
            var tabla3 = $("<table>");
            tabla3.addClass("tabla-jsoncargadosdetenidos");
            tabla3.css("display", "none"); // Ocultar la tabla
            

       

            // Crear la cabecera de la tabla
            var cabecera3 = $("<tr>");
        cabecera3.append($("<th>").text("Unidad"));
cabecera3.append($("<th>").text("Origen"));
cabecera3.append($("<th>").text("Destino"));
cabecera3.append($("<th>").text("Ultimo mensaje"));
cabecera3.append($("<th>").text("Doble"));
cabecera3.append($("<th>").text("Ultimo mensaje"));
cabecera3.append($("<th>").text("Caja"));
cabecera3.append($("<th>").text("Ultimo menssaje"));
cabecera3.append($("<th>").text("Bitacora"));
tabla3.append(cabecera3);
            

 // Recorrer los elementos del JSON
for (var i = 0; i < json3.length; i++) {
var elemento3 = json3[i];
var lng = elemento3.longitud;
var lat = elemento3.Latitud;
     var unidad = elemento3.Unidad;
     

     
// Crear una fila para cada objeto
var fila3 = $("<tr>");

// Agregar las celdas con los datos correspondientes
fila3.append($("<td>").text(elemento3.Unidad));
fila3.append($("<td>").text(elemento3.Origen));
fila3.append($("<td>").text(elemento3.Destino));
fila3.append($("<td>").text(elemento3.Diferencia_tiempo));
fila3.append($("<td>").text(elemento3.Doble));
fila3.append($("<td>").text(elemento3.Doble_Reportando));
fila3.append($("<td>").text(elemento3.Caja));
fila3.append($("<td>").text(elemento3.Caja_Reportando));

                unidadesCount3++;

                $("#botonTotalcargadosdet").text("Unidades Cargadas Detenidas (" + unidadesCount3 + ")");

                // Crear una variable y un botón de alternancia para cada objeto
                var toggleButton3 = $("<input>").attr({
                    type: "checkbox",
                    id: "toggle" + i
                }).on("click", createToggleButtonHandler3(lat, lng, elemento3.Unidad, elemento3.Campos));

                fila3.append($("<td>").append(toggleButton3));

                // Agregar la fila a la tabla
                tabla3.append(fila3);
            }
            // Agregar la tabla al contenedor
            $("#contenedor3").append(tabla3);
            
                        


            // Llamar a la función nuevamente después de un tiempo para actualizar los datos
            setTimeout(cargarJSON3, 60000); // Actualizar cada 95 segundos (5000 ms)
        },
        error: function (xhr, status, error) {
            console.error("Error al cargar el archivo JSON");
        }
    });
}

// Función para manejar el clic en el botón de alternancia
function createToggleButtonHandler3(lat, lng, unidad, campos) {
    return function() {
        var index3 = parseInt($(this).attr("id").substr(6));

        if ($(this).is(":checked")) {
            markers[index3] = addMarker(lat, lng, unidad);

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
            markers[index3].setMap(null);
        }
    };
}

$(document).ready(function () {
    cargarJSON3();
});
