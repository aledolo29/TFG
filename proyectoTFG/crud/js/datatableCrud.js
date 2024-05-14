addEventListener("load", inicializarEventos);
function inicializarEventos() {
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
    $.fn.dataTable.ext.type.order["custom-tilde-sort-desc"] = function (a, b) {
      return a < b ? 1 : a > b ? -1 : 0;
    };
    $("#TablaEmpleados").DataTable({
      order: [[0, "asc"]],
      columnDefs: [{ orderable: false, targets: [5] }],
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

    $("#TablaClientes").DataTable({
      order: [[0, "asc"]],
      columnDefs: [{ orderable: false, targets: [6] }],
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

    $("#TablaUsuarios").DataTable({
      order: [[0, "asc"]],
      columnDefs: [{ orderable: false, targets: [3] }],
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

    $("#TablaNominas").DataTable({
      order: [[0, "asc"]],
      columnDefs: [{ orderable: false, targets: [3] }],
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
