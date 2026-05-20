import { Modal } from "../components/Modal.js";

document.addEventListener("DOMContentLoaded", async () => {
  const data = await getDataRefrigerado();
  $("#cont-refrigerado").html(data.length);
});

const getDataRefrigerado = async () => {
  const url =
    "http://ws4cjdg.com/OPERACION_GUZMAN/src/services/refrigerado/refrigerado.php";
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
};

const getTableDataRefrigerado = async () => {
  const data = await getDataRefrigerado();
  let body_table = ``;

  console.log(data);

  if (data.length) {
    $(".table-other").hide();

    data.forEach((element) => {
      body_table += `
                    <tr class="">
                        <td>${element.is_caja ? element.tracto : element.name}</td>
                        <td>${element.is_caja ? element.name : "Sin caja registrada"}</td>
                        <td>${element.Temperatura}</td>
                        <td>${element.Ultimo_mensaje}</td>
                        <td class="d-flex align-content-center justify-content-center">
                            <button class="btn btn-sm btn-warning"${element.historico_temperatura ? "" : "disabled"}  type="button" onClick="showTemperatura('${element.Unidad}')" >
                                ver historico de temperatura
                            </button>
                        </td>
                    </tr>`;
    });

    $("#root-tables-data").html(`
            <table class="tabla-jsoncargando" id="root-table-seco">
                <thead>
                    <tr>
                    <th scope="col">Tracto</th>
                    <th scope="col">Caja</th>
                    <th scope="col">Temperatura</th>
                    <th scope="col">Ultimo mensaje</th>
                    <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody class="table-light">
                    ${body_table}
                </tbody>
            </table>`);
  }

  // $('#root-table-seco').DataTable();
};
window.getTableDataRefrigerado = getTableDataRefrigerado;

const showTemperatura = async (unit_name) => {

  const data = await getDataRefrigerado();

  data.forEach((unit) => {

    if (unit_name === unit.Unidad) {

      console.log(unit);

      // =====================================
      // HISTORICO INVERTIDO
      // =====================================
      const historico =
        [...(unit.historico_temperatura || [])]
          .reverse();

      // =====================================
      // BODY
      // =====================================
      const body = `
      
        <div class="container-fluid">
          <div class="row mb-3">
            <div class="col-12">
              <div class="alert alert-info">
                <strong>Unidad:</strong>
                ${unit.Unidad}
                <br>
                <strong>Total registros:</strong>
                ${historico.length}
              </div>
            </div>
          </div>

          <!-- TABLA -->
          <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">

            <table class="table table-bordered table-hover table-striped align-middle">
              <thead class="table-dark" style=" position: sticky; top: 0; z-index: 10;">
                <tr>
                  <th>#</th>
                  <th>Fecha</th>
                  <th>Temperatura</th>
                </tr>
              </thead>
              <tbody>

                ${historico.map((item, index) => {

                  let badge = "bg-success";

                  if (item.value > 8) {
                    badge = "bg-danger";
                  } else if (item.value > 5) {
                    badge = "bg-warning text-dark";
                  }

                  return `
                    <tr>
                      <td>${index + 1}</td>
                      <td>${item.date}</td>
                      <td>
                        <span class="badge ${badge} fs-6">
                          ${Number(item.value).toFixed(1)} °C
                        </span>
                      </td>
                    </tr>
                  `;

                }).join("")}

              </tbody>
            </table>
          </div>
        </div>
      `;

      // =====================================
      // MODAL
      // =====================================
      const element = {
        title: `Historico de temperatura unidad: ${unit.Unidad}`,
        body,
      };

      $("#root-modal").remove();

      $("body").append(
        Modal(element)
      );

      const modalElement =
        document.getElementById("root-modal");

      const modal =
        new bootstrap.Modal(modalElement);

      modal.show();
    }
  });
};
window.showTemperatura = showTemperatura;
