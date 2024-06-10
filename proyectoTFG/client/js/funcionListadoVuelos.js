// ---------------------------------------
// Confirmación de reserva
$(document).on("click", ".enlace_reservarVuelo", function (e) {
  e.preventDefault();
  var listado = $("#listadoVuelos__vuelos");
  var vueloSeleccionado = $("#vueloSeleccionado");
  listado.fadeOut("slow");

  // Obtener los datos del vuelo seleccionado
  var enlace = $(this);
  var href = enlace.attr("href");
  var vuelo = href.split("?")[1];
  vuelo = decodeURIComponent(vuelo.split("&")[0].split("=")[1]);
  vuelo = JSON.parse(vuelo);
  var vueloJson = JSON.stringify(vuelo);
  var intervalo = href.split("?")[1];
  intervalo = decodeURIComponent(intervalo.split("&")[1].split("=")[1]);

  // Mostrar los datos del vuelo seleccionado

  var fechaHoraSalida = vuelo.vuelo_Fecha_Hora_Salida.split(" ");
  var fechaHoraLlegada = vuelo.vuelo_Fecha_Hora_Llegada.split(" ");
  var horaSalida = fechaHoraSalida[1].substring(0, 5);
  var horaLlegada = fechaHoraLlegada[1].substring(0, 5);
  var fechaSalida = formatearFecha(fechaHoraSalida[0]);
  var fechaLlegada = formatearFecha(fechaHoraLlegada[0]);
  var intervalo = calcularIntervaloFechas(
    vuelo.vuelo_Fecha_Hora_Salida,
    vuelo.vuelo_Fecha_Hora_Llegada
  );
  cargarCiudades().then(() => {
    var contenido_vueloSeleccionado = $(`
  <div class="vueloSeleccionado__item d-flex flex-column align-items-center justify-content-between p-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center w-100">
      <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex flex-column justify-content-center">
          <p class="fs-2 fw-bold">${horaSalida} <span class="listadoVuelos__guiones">-----------</span></p>
          <p class="fs-5">${fechaSalida}</p>
          <p class="fs-4 fw-semibold">${
            ciudades[vuelo.vuelo_AeropuertoSalida]
          } (${vuelo.vuelo_AeropuertoSalida})</p>
        </div>
        <div class="d-flex flex-column mb-4 align-items-center justify-content-center mx-5 mx-sm-0">
          <img class="listadoVuelos__imagen img-fluid mx-3 w-50 mb-2" src="../../../build/assets/media/icons8-avion-32.png">
          <p class="listadoVuelos__intervalo fs-5 fw-bold mb-5">${
            intervalo[0]
          }h ${intervalo[1]}min.</p>
        </div>
        <div class="d-flex flex-column align-items-end">
          <p class="fs-2 fw-bold"><span class="listadoVuelos__guiones">-----------</span> ${horaLlegada}</p>
          <p class="fs-5">${fechaLlegada}</p>
          <p class="fs-4 fw-semibold">${
            ciudades[vuelo.vuelo_AeropuertoLlegada]
          } (${vuelo.vuelo_AeropuertoLlegada})</p>
        </div>
      </div>
      <div class="d-flex flex-column justify-content-center align-items-center">
        <p class="fs-2 fw-bold text-center">${vuelo.precio}€<br>por persona</p>
      </div>
    </div>
    <div class="d-flex justify-content-center align-items-center mt-5 w-100">
      <a href="elegirAsiento.html?vueloSeleccionado=${encodeURIComponent(
        vueloJson
      )}" class="btn vueloSeleccionado__btn text-center fs-4 fw-bold px-5 py-4 mt-4 mt-xl-0 rounded-4 border-0">Rellenar datos de pasajero</a>
    </div>
   </div>
  `);
    vueloSeleccionado.append(contenido_vueloSeleccionado);
    vueloSeleccionado.fadeIn("slow");
  });
});

// ---------------------------------------
// Guardar vuelo seleccionado
$(document).on("click", ".vueloSeleccionado__btn", function (e) {
  e.preventDefault();
  seguridad();
  var enlace = $(this);
  var href = enlace.attr("href");
  var vuelo = href.split("=")[1];
  sessionStorage.setItem("vueloSeleccionado", vuelo);
  window.location.href = href;
});

// ---------------------------------------
// Cargar listado de vuelos
$(document).ready(function () {
  var listadoVuelos = $("#listadoVuelos__vuelos");
  listadoVuelos.empty();
  // Recuperar los datos de los vuelos IDA de localStorage
  var dataVuelo = JSON.parse(localStorage.getItem("vuelos"));

  // Si no hay vuelos
  if (dataVuelo == null || dataVuelo.length == 0) {
    window.location.href =
      "https://ruizgijon.ddns.net/domingueza/TFG/proyectoTFG/client/archivos/error.html";
  }

  // Mostrar los vuelos
  cargarCiudades().then(() => {
    // Mostrar los vuelos de IDA
    if (dataVuelo !== null) {
      Object.values(dataVuelo).forEach((vuelo) => {
        var vueloJson = JSON.stringify(vuelo);
        var fechaHoraSalida = vuelo.vuelo_Fecha_Hora_Salida.split(" ");
        var fechaHoraLlegada = vuelo.vuelo_Fecha_Hora_Llegada.split(" ");
        var horaSalida = fechaHoraSalida[1].substring(0, 5);
        var horaLlegada = fechaHoraLlegada[1].substring(0, 5);
        var fechaSalida = formatearFecha(fechaHoraSalida[0]);
        var fechaLlegada = formatearFecha(fechaHoraLlegada[0]);
        var intervalo = calcularIntervaloFechas(
          vuelo.vuelo_Fecha_Hora_Salida,
          vuelo.vuelo_Fecha_Hora_Llegada
        );
        var vueloHTML = $(`
            <div class="listadoVuelos__item d-flex flex-column flex-xl-row align-items-center justify-content-between m-auto p-4 mb-3">
              <div class="d-flex justify-content-evenly align-items-center">
                <div class="d-flex flex-column justify-content-center">
                  <p class="fs-3">${horaSalida} <span class="listadoVuelos__guiones">-----------</span></p>
                  <p class="fs-6">${fechaSalida}</p>
                  <p class="fs-5">${ciudades[vuelo.vuelo_AeropuertoSalida]} (${
          vuelo.vuelo_AeropuertoSalida
        })</p>
                </div>
                <div class="d-flex flex-column mb-4 align-items-center justify-content-center mx-5 mx-sm-0">
                  <img class="listadoVuelos__imagen img-fluid mx-3 w-50 mb-2" src="../../../build/assets/media/icons8-avion-32.png">
                  <p class="listadoVuelos__intervalo fs-5 fw-bold mb-5">${
                    intervalo[0]
                  }h ${intervalo[1]}min.</p>
                </div>
                <div class="d-flex flex-column align-items-end">
                  <p class="fs-3"><span class="listadoVuelos__guiones">-----------</span> ${horaLlegada}</p>
                  <p class="fs-6">${fechaLlegada}</p>
                  <p class="fs-5">${ciudades[vuelo.vuelo_AeropuertoLlegada]} (${
          vuelo.vuelo_AeropuertoLlegada
        })</p>
                </div>
              </div>
              <div class="d-flex flex-column justify-content-center align-items-center">
                <p class="fs-4 fw-bold text-center">${
                  vuelo.precio
                }€<br>por persona</p>
              </div>
              <div class="listadoVuelos__containerbtn d-flex justify-content-center align-items-center">
              <a href="listadoVuelos.html?vuelo=${encodeURIComponent(
                vueloJson
              )}&intervarlo=${intervalo}" class="enlace_reservarVuelo btn listadoVuelos__item__btn text-center fs-5 px-5 py-3 mt-4 mt-xl-0 rounded-4 border-0">Reservar</a>
              </div>
            </div>
          `);
        listadoVuelos.append(vueloHTML);
      });
    }
  });
});
