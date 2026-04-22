document.addEventListener("DOMContentLoaded", async () => {
    const data = await getDataVariacion();
    $("#cont-Variacion").html(data.length);

});


const getDataVariacion = async () => {
    const url = "http://ws4cjdg.com/OPERACION_GUZMAN/src/services/variacion_temperatura/variacion_temperatura.php";
    try {
        const response = await fetch(url);

        if (!response.ok) {
            throw new Error("Error en la petición");
        }

        const data = await response.json();
        return data;

    } catch (error) {
        console.error("Error:", error);
    }
}

const getTableDataVariacion = async () => {
    const data = await getDataVariacion()
    let body_table = `` 

    if (data.length) {
        $(".table-other").hide()

        data.forEach(element => {
            body_table += `
                    <tr class="">
                        <td>${element.name}</td>
                        <td>${element.Temperatura}</td>
                        <td>${element.Origen}</td>
                        <td>${element.Destino}</td>
                        <td>${element.Ultimo_mensaje}</td>
                    </tr>`
        });
    
        $("#root-tables-data").html(`
            <table class="tabla-jsoncargando" id="root-table-Variacion">
                <thead>
                    <tr>
                    <th scope="col">Unidad</th>
                    <th scope="col">Temperatura</th>
                    <th scope="col">Origen</th>
                    <th scope="col">Destino</th>
                    <th scope="col">Ultimo mensaje</th>
                    </tr>
                </thead>
                <tbody class="table-light">
                    ${body_table}
                </tbody>
            </table>`)
    }

    // $('#root-table-Variacion').DataTable();
}
window.getTableDataVariacion = getTableDataVariacion;