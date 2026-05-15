document.addEventListener("DOMContentLoaded", async () => {
    const data = await getDataRefrigerado();
    $("#cont-refrigerado").html(data.length);
    
});


const getDataRefrigerado = async () => {
    const url = "http://ws4cjdg.com/OPERACION_GUZMAN/src/services/refrigerado/refrigerado.php";
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

const getTableDataRefrigerado = async () => {
    const data = await getDataRefrigerado()
    let body_table = `` 

    console.log(data);

    if (data.length) {
        $(".table-other").hide()

        data.forEach(element => {
            body_table += `
                    <tr class="">
                        <td>${ (element.is_caja) ? element.tracto : element.name }</td>
                        <td>${ (element.is_caja) ? element.name : 'Sin caja registrada' }</td>
                        <td>${element.Temperatura}</td>
                        <td>${element.Ultimo_mensaje}</td>
                    </tr>`
        });
    
        $("#root-tables-data").html(`
            <table class="tabla-jsoncargando" id="root-table-seco">
                <thead>
                    <tr>
                    <th scope="col">Tracto</th>
                    <th scope="col">Caja</th>
                    <th scope="col">Temperatura</th>
                    <th scope="col">Ultimo mensaje</th>
                    </tr>
                </thead>
                <tbody class="table-light">
                    ${body_table}
                </tbody>
            </table>`)
    }

    // $('#root-table-seco').DataTable();
}
window.getTableDataRefrigerado = getTableDataRefrigerado;