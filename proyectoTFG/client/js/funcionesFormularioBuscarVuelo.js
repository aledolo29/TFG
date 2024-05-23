// BUSQUEDA VUELOS

// Aeropuerto origen
var aeropuertos_origen = $(".aeropuerto_origen");
aeropuertos_origen.change(function () {
  var valor = $(this).val().toLowerCase();
  if (valor.length == 0) {
    aeropuertos_origen.val("");
    return;
  }
});
$(".aeropuerto_origen").keyup(function () {
  var valor = $(this).val().toLowerCase();
  var lista = $(".lista_aeropuertos_origen");
  var contador = 0;
  if (valor.length < 3) {
    lista.hide();
    return;
  }
  fetch("https://api.npoint.io/0ae89dcddb751bee38ef").then((res) => {
    res.json().then((data) => {
      lista.empty();
      lista.show();
      data.forEach((aeropuerto) => {
        if (aeropuerto.city.toLowerCase().includes(valor)) {
          var opcion = $(
            `<li class='dropdown-item dropdown__aeropuertos__item fw-bold fs-4'></li>`
          );
          opcion.html(
            aeropuerto.city +
              ` (${aeropuerto.iata})<br><span class='fs-5'>${aeropuerto.country}</span>`
          );
          opcion.val(aeropuerto.id);
          lista.append(opcion);
          contador++;
        }
      });
      if (contador == 0) {
        var opcion = $(
          "<li class='dropdown-item dropdown__aeropuertos__item fs-4'></li>"
        );
        opcion.html("No se han encontrado resultados de origen para " + valor);
        lista.append(opcion);
      }
    });
  });
});
// Aeropuerto destino
var aeropuertos_destino = $(".aeropuerto_destino");
aeropuertos_destino.change(function () {
  var valor = $(this).val().toLowerCase();
  if (valor.length == 0) {
    aeropuertos_destino.val("");
    return;
  }
});
$(".aeropuerto_destino").keyup(function () {
  var valor = $(this).val().toLowerCase();
  var lista = $(".lista_aeropuertos_destino");
  var contador = 0;
  if (valor.length < 3) {
    lista.hide();
    return;
  }
  fetch("https://api.npoint.io/0ae89dcddb751bee38ef").then((res) => {
    res.json().then((data) => {
      lista.empty();
      lista.show();
      data.forEach((aeropuerto) => {
        if (aeropuerto.city.toLowerCase().includes(valor)) {
          var opcion = $(
            `<li class='dropdown-item dropdown__aeropuertos__item fw-bold fs-4'></li>`
          );
          opcion.html(
            aeropuerto.city +
              ` (${aeropuerto.iata})<br><span class='fs-5'>${aeropuerto.country}</span>`
          );
          opcion.val(aeropuerto.id);
          lista.append(opcion);
          contador++;
        }
      });
      if (contador == 0) {
        var opcion = $(
          "<li class='dropdown-item dropdown__aeropuertos__item fs-4'></li>"
        );
        opcion.html("No se han encontrado resultados de destino para " + valor);
        lista.append(opcion);
      }
    });
  });
});

// Seleccionar aeropuerto origen
$(".lista_aeropuertos_origen").on("click", "li", function () {
  var text = $(this).text();
  posParentesis = text.indexOf(")");
  text = text.substring(0, posParentesis + 1);
  $(".aeropuerto_origen").val(text);
  $(".lista_aeropuertos_origen").hide();
  $(".id_aeropuerto_origen").val($(this).val()); // Guardar el id del aeropuerto origen (oculto)
});

// Seleccionar aeropuerto destino
$(".lista_aeropuertos_destino").on("click", "li", function () {
  var text = $(this).text();
  posParentesis = text.indexOf(")");
  text = text.substring(0, posParentesis + 1);
  $(".aeropuerto_destino").val(text);
  $(".lista_aeropuertos_destino").hide();
  $(".id_aeropuerto_destino").val($(this).val()); // Guardar el id del aeropuerto destino (oculto)
});

// ---------------------------------------
// Vuelo ida
var buscarVueloBtn_Ida = $("#buscaVuelos_btn_ida");
buscarVueloBtn_Ida.click(function (e) {
  e.preventDefault();
  var id_aeropuerto_origen = $("#id_aeropuerto_origen_ida").val();
  var id_aeropuerto_destino = $("#id_aeropuerto_destino_ida").val();
  var fecha = $("#fecha_ida").val();
  var pasajeros = $("#pasajeros_ida").val();
  var codAeropuerto_Origen = "";
  var codAeropuerto_Destino = "";
  var coordenadasAeropuerto_Origen = "";
  var coordenadasAeropuerto_Destino = "";
  var intervalo = [];

  fetch("https://api.npoint.io/0ae89dcddb751bee38ef").then((res) => {
    res.json().then((data) => {
      data.forEach((aeropuerto) => {
        if (aeropuerto.id == id_aeropuerto_origen) {
          codAeropuerto_Origen = aeropuerto.iata;
          coordenadasAeropuerto_Origen = aeropuerto.coordinates_wkt;
        }
        if (aeropuerto.id == id_aeropuerto_destino) {
          coordenadasAeropuerto_Destino = aeropuerto.coordinates_wkt;
          codAeropuerto_Destino = aeropuerto.iata;
        }
      });
      intervalo = obtenerIntervalo(
        coordenadasAeropuerto_Origen,
        coordenadasAeropuerto_Destino
      );
      fetch(
        "http://localhost/TFG/proyectoTFG/server/public/api/buscarVueloIda",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            origen: codAeropuerto_Origen,
            destino: codAeropuerto_Destino,
            fecha: fecha,
            pasajeros: pasajeros,
            intervalo: intervalo,
          }),
        }
      ).then((res) => {
        if (res.status == 200) {
          res.json().then((data) => {
            data.forEach((vuelo) => {
              var intervalo = calcularIntervaloFechas(
                vuelo.vuelo_Fecha_Hora_Salida,
                vuelo.vuelo_Fecha_Hora_Llegada
              );
              vuelo.precio = calcularPrecioVuelo(intervalo);
            });
            localStorage.setItem("vuelosIda", JSON.stringify(data));
            if (localStorage.getItem("vuelosIdaVuelta") != null) {
              localStorage.removeItem("vuelosIdaVuelta");
            }
            window.location.href =
              "http://localhost/TFG/proyectoTFG/client/archivos/listadoVuelos.html";
          });
        } else {
          alert("Error en el servidor");
        }
      });
    });
  });
});

//---------------------------------------
// Vuelo ida y vuelta
var buscarVueloBtn_IdaVuelta = $("#buscaVuelos_btn_idaVuelta");
buscarVueloBtn_IdaVuelta.click(function (e) {
  e.preventDefault();
  var id_aeropuerto_origen = $("#id_aeropuerto_origen_idaVuelta").val();
  var id_aeropuerto_destino = $("#id_aeropuerto_destino_idaVuelta").val();
  var fecha_ida = $("#fecha_ida_idaVuelta").val();
  var fecha_vuelta = $("#fecha_vuelta_idaVuelta").val();
  var pasajeros = $("#pasajeros_idaVuelta").val();
  var codAeropuerto_Origen = "";
  var codAeropuerto_Destino = "";
  var intervalo = [];

  fetch("https://api.npoint.io/0ae89dcddb751bee38ef").then((res) => {
    res.json().then((data) => {
      data.forEach((aeropuerto) => {
        if (aeropuerto.id == id_aeropuerto_origen) {
          codAeropuerto_Origen = aeropuerto.iata;
          coordenadasAeropuerto_Origen = aeropuerto.coordinates_wkt;
        }
        if (aeropuerto.id == id_aeropuerto_destino) {
          coordenadasAeropuerto_Destino = aeropuerto.coordinates_wkt;
          codAeropuerto_Destino = aeropuerto.iata;
        }
      });
      intervalo = obtenerIntervalo(
        coordenadasAeropuerto_Origen,
        coordenadasAeropuerto_Destino
      );
      fetch(
        "http://localhost/TFG/proyectoTFG/server/public/api/buscarVueloIdaVuelta",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            origen: codAeropuerto_Origen,
            destino: codAeropuerto_Destino,
            fechaIda: fecha_ida,
            fechaVuelta: fecha_vuelta,
            pasajeros: pasajeros,
            intervalo: intervalo,
          }),
        }
      ).then((res) => {
        if (res.status == 200) {
          res.json().then((data) => {
            data.forEach((vuelo) => {
              var intervalo = calcularIntervaloFechas(
                vuelo.vuelo_Fecha_Hora_Salida,
                vuelo.vuelo_Fecha_Hora_Llegada
              );
              vuelo.precio = calcularPrecioVuelo(intervalo);
            });
            localStorage.setItem("vuelosIdaVuelta", JSON.stringify(data));
            if (localStorage.getItem("vuelosIda") != null) {
              localStorage.removeItem("vuelosIda");
            }
            window.location.href =
              "http://localhost/TFG/proyectoTFG/client/archivos/listadoVuelos.html";
          });
        } else {
          alert("Error en el servidor");
        }
      });
    });
  });
});
