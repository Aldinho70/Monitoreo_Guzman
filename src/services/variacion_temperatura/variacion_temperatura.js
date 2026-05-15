document.addEventListener("DOMContentLoaded", async () => {
    const data = await getDataVariacion();
    if(data.length){
        $("#cont-variacion_temperatura").html(data.length);
        $("#btn-variacion-temperatura").removeClass('btn-secondary')
        $("#btn-variacion-temperatura").addClass('btn-danger')
        $("#btn-variacion-temperatura").addClass('pulse')
    }

});


const getDataVariacion = async () => {
    const url = "http://ws4cjdg.com/OPERACION_GUZMAN/src/services/variacion_temperatura/variacion_temperatura.php";
    try {
        const response = await fetch(url);

        if (!response.ok) {
            throw new Error("Error en la petición");
        }

        const data = await response.json();
        console.log(data);
        
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
        $("#btn-variacion-temperatura").removeClass('pulse')
        $("#btn-variacion-temperatura").removeClass('btn-danger')
        $("#btn-variacion-temperatura").addClass('btn-secondary')

        data.forEach(element => {
            body_table += `
                    <tr class="">
                        <td>${element.name}</td>
                        <td>${element.Temperatura}</td>
                        <td>${element.Ultimo_mensaje}</td>
                    </tr>`
        });
    
        $("#root-tables-data").html(`
            <table class="tabla-jsoncargando" id="root-table-Variacion">
                <thead>
                    <tr>
                    <th scope="col">Unidad</th>
                    <th scope="col">Temperatura</th>
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