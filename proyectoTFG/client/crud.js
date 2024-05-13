addEventListener("load", inicializarEventos);
var conexion1;
const tabla = document.getElementById("TablaEmpleados_body");

function inicializarEventos() {
  //   document.getElementById("btnInsertar").addEventListener("click", insertar);
  //   document.getElementById("btnModificar").addEventListener("click", modificar);
  //   document.getElementById("btnEliminar").addEventListener("click", eliminar);
  //   document.getElementById("btnBuscar").addEventListener("click", buscar);

  tabla.innerHTML = "";

  conexion1 = new XMLHttpRequest();
  conexion1.onreadystatechange = cargarTabla;
  conexion1.open("GET", "/TFG/proyectoTFG/server/cargarEmpleados.php", true);
  conexion1.send();
}

function cargarTabla() {
  if (conexion1.readyState == 4 && conexion1.status == 200) {
    var empleados = JSON.parse(conexion1.responseText);
    empleados.forEach((empleado) => {
      var fila = tabla.insertRow(tabla.rows.length);
      fila.innerHTML = `
      <td>
      <div class="d-flex align-items-center">
        <div class="symbol symbol-20px me-2">
          <span class="symbol-label bg-light-info">
              <!--begin::Svg Icon | path: icons/duotune/finance/fin006.svg-->
              <span class="svg-icon svg-icon-info">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none">
                  <path opacity="0.3" d="M20 15H4C2.9 15 2 14.1 2 13V7C2 6.4 2.4 6 3 6H21C21.6 6 22 6.4 22 7V13C22 14.1 21.1 15 20 15ZM13 12H11C10.5 12 10 12.4 10 13V16C10 16.5 10.4 17 11 17H13C13.6 17 14 16.6 14 16V13C14 12.4 13.6 12 13 12Z" fill="#835fd3"></path>
                  <path d="M14 6V5H10V6H8V5C8 3.9 8.9 3 10 3H14C15.1 3 16 3.9 16 5V6H14ZM20 15H14V16C14 16.6 13.5 17 13 17H11C10.5 17 10 16.6 10 16V15H4C3.6 15 3.3 14.9 3 14.7V18C3 19.1 3.9 20 5 20H19C20.1 20 21 19.1 21 18V14.7C20.7 14.9 20.4 15 20 15Z" fill="#5d38af"></path>
                </svg>
              </span>
              <!--end::Svg Icon-->
          </span>
      </div>
      <div class="d-flex justify-content-start flex-column">${empleado.empl_Nombre}</div>
    </div>
    </td>
      <td>${empleado.empl_Apellidos}</td>
      <td>${empleado.empl_Usuario}</td>
      <td>${empleado.empl_Tipo_Usuario}</td>
      <td>${empleado.empl_Estado}</td>`;
    });

    $(document).ready(function () {
      // Define la función de comparación personalizada para convertir caracteres con tildes a su forma base.
      $.fn.dataTable.ext.type.order["custom-tilde-sort-pre"] = function (data) {
        return data
          .normalize("NFD")
          .replace(/[\u0300-\u036f]/g, "")
          .toLowerCase();
      };

      // Define la función de comparación para ordenar en ascendente utilizando la función personalizada.
      $.fn.dataTable.ext.type.order["custom-tilde-sort-asc"] = function (a, b) {
        return a < b ? -1 : a > b ? 1 : 0;
      };

      // Define la función de comparación para ordenar en descendente utilizando la función personalizada.
      $.fn.dataTable.ext.type.order["custom-tilde-sort-desc"] = function (
        a,
        b
      ) {
        return a < b ? 1 : a > b ? -1 : 0;
      };
      $("#TablaEmpleados").DataTable({
        order: [[0, "asc"]],
        language: {
          lengthMenu: "Mostrar _MENU_ registros por página",
          info: "Mostrando pagina _PAGE_ de _PAGES_",
          infoEmpty: "No hay registros disponibles",
          infoFiltered: "(filtrada de _MAX_ registros)",
          loadingRecords: "Cargando...",
          processing: "Procesando...",
          search: "Buscar:",
          zeroRecords: "No se encontraron registros coincidentes",
          paginate: {
            next: "Siguiente",
            previous: "Anterior",
          },
        },
      });
    });
  }
}
