// Variables globales para la carga de ciudades
var ciudades = {};
var datosCargados = false;

// Para saber si ha iniciado sesión
$(document).ready(function () {
  if (localStorage.getItem("nombre") != null) {
    $("#btn_login_text").text("Hola " + localStorage.getItem("nombre"));
  } else {
    $("#btn_login_text").text("Iniciar sesión");
  }
});

// Botón de ir hacia arriba
$(document).ready(function () {
  $("#irHaciaArriba").hide();
  $("#irHaciaArriba").click(irHaciaArriba);
});

$(document).scroll(function () {
  if ($(this).scrollTop() > 100) {
    $("#irHaciaArriba").fadeIn();
  } else {
    $("#irHaciaArriba").fadeOut();
  }
});

// Ocultar reseñas
$("#resenas_ocultas").hide();

// REGSITRO DE CLIENTE
// boton formulario Registro
$("#btnRegistro").click(function () {
  var nombre = $("#cliente_Nombre").val();
  var apellidos = $("#cliente_Apellidos").val();
  var usuario = $("#cliente_Usuario").val();
  var password = $("#cliente_Password").val();
  var password2 = $("#cliente_Password2").val();
  var dni = $("#cliente_DNI").val();
  var correo = $("#cliente_Correo").val();
  var telefono = $("#cliente_Telefono").val();

  if (password == password2) {
    fetch(
      "http://localhost/TFG/proyectoTFG/server/public/api/registroCliente",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          nombre: nombre,
          apellidos: apellidos,
          usuario: usuario,
          password: password,
          dni: dni,
          correo: correo,
          telefono: telefono,
        }),
      }
    ).then((res) => {
      if (res.status == 200) {
        var mensaje_correcto = $("#mensaje_correcto");
        var mensaje_error = $("#mensaje_error");

        res.json().then((data) => {
          if (data.correcto) {
            mensaje_correcto.addClass("alert alert-success");
            mensaje_correcto.text(data.correcto.toString());
            mensaje_error.text("");
            mensaje_error.removeClass("alert alert-danger");
            setTimeout(() => {
              window.location.href =
                "http://localhost/TFG/proyectoTFG/client/archivos/index.html";
            }, 3000);
          } else {
            mensaje_error.addClass("alert alert-danger");
            mensaje_error.text(data.error, toString());
            mensaje_correcto.text("");
            mensaje_correcto.removeClass("alert alert-success");
          }
        });
      } else {
        alert("Error en el servidor");
      }
    });
  } else {
    alert("Las contraseñas no coinciden");
  }
});

// BUSQUEDA AEROPUERTOS
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
  if (valor.length == 0) {
    aeropuertos_origen.val("");
    return;
  }
  fetch("https://api.npoint.io/0ae89dcddb751bee38ef").then((res) => {
    res.json().then((data) => {
      lista.empty();
      lista.show();
      data.forEach((aeropuerto) => {
        if (aeropuerto.city.toLowerCase().includes(valor)) {
          var opcion = $(
            "<li class='dropdown-item dropdown__aeropuertos__item fw-bold fs-4'></li>"
          );
          opcion.html(
            aeropuerto.city +
              ` (${aeropuerto.iata})<br><span class='fs-5 fw-light'>${aeropuerto.country}</span>`
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

// BUSQUEDA VUELOS

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

// ---------------------------------------
// Cargar listado de vuelos
$(document).ready(function () {
  var listadoVuelos = $("#listadoVuelos__vuelos");
  listadoVuelos.empty();
  // Recuperar los datos de los vuelos IDA de localStorage
  var dataIda = JSON.parse(localStorage.getItem("vuelosIda"));

  // Recuperar los datos de los vuelos IDA y VUELTA de localStorage
  var dataIdaVuelta = JSON.parse(localStorage.getItem("vuelosIdaVuelta"));

  // Si no hay vuelos
  if (
    (dataIda == null || dataIda.length == 0) &&
    (dataIdaVuelta == null || dataIdaVuelta.length == 0)
  ) {
    $("#listadoVuelos__titulo").hide();
    var mensaje = $(`
      <div class="d-flex flex-column align-items-center justify-content-center">
        <img class="img-fluid w-50" src="../../assets/media/falloVuelos.png">
        <p class="fs-4 fw-bold text-center">No se han encontrado vuelos</p>
      </div>
    `);
    listadoVuelos.append(mensaje);
    return;
  }

  // Mostrar los vuelos
  cargarCiudades().then(() => {
    // Mostrar los vuelos de IDA
    if (dataIda !== null) {
      dataIda.forEach((vuelo) => {
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
                <img class="listadoVuelos__imagen img-fluid mx-3 w-50 mb-2" src="../../assets/media/icons8-avion-32.png">
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

      // Mostrar los vuelos de IDA y VUELTA
    } else if (dataIdaVuelta !== null) {
      dataIdaVuelta.forEach((vuelo) => {
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
                <img class="listadoVuelos__imagen img-fluid mx-3 w-50 mb-2" src="../../assets/media/icons8-avion-32.png">
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
              <a href="" class="btn listadoVuelos__item__btn text-center fs-5 px-5 py-3 mt-4 mt-xl-0 rounded-4 border-0">Reservar</a>
            </div>
          </div>
        `);
        listadoVuelos.append(vueloHTML);
      });
    }
  });
});

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
<div class="vueloSeleccionado__item d-flex flex-column align-items-center justify-content-between m-auto p-4 mb-3">
  <div class="d-flex justify-content-around align-items-center w-100">
    <div class="d-flex justify-content-between align-items-center">
      <div class="d-flex flex-column justify-content-center">
        <p class="fs-2 fw-bold">${horaSalida} <span class="listadoVuelos__guiones">-----------</span></p>
        <p class="fs-5">${fechaSalida}</p>
        <p class="fs-4 fw-semibold">${
          ciudades[vuelo.vuelo_AeropuertoSalida]
        } (${vuelo.vuelo_AeropuertoSalida})</p>
      </div>
      <div class="d-flex flex-column mb-4 align-items-center justify-content-center mx-5 mx-sm-0">
        <img class="listadoVuelos__imagen img-fluid mx-3 w-50 mb-2" src="../../assets/media/icons8-avion-32.png">
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
  <a href="" class="btn vueloSeleccionado__btn text-center fs-4 fw-bold px-5 py-4 mt-4 mt-xl-0 rounded-4 border-0">Rellenar datos de pasajero</a>
</div>
 </div>
`);
    vueloSeleccionado.append(contenido_vueloSeleccionado);
    vueloSeleccionado.fadeIn("slow");
  });
});

// ---------------------------------------
// Rellenar datos de pasajero
$(document).ready(pintarAsientos);
// FUNCIONES 💻
// ---------------------------------------
function mostrar_resenas(e) {
  e.preventDefault();
  var enlace_resenas = $("#enlace_resenas");
  var resenas_ocultas = $("#resenas_ocultas");
  if (enlace_resenas.text() == "Ver más") {
    enlace_resenas.text("Ver menos");
    resenas_ocultas.fadeIn("slow");
  } else {
    enlace_resenas.text("Ver más");
    resenas_ocultas.hide();
  }
}

// ---------------------------------------
function cambiarOrigenDestino(e) {
  e.preventDefault();
  var origen = $("#aeropuerto_origen");
  var destino = $("#aeropuerto_destino");
  var origen_val = origen.val();
  var destino_val = destino.val();
  destino.val(origen_val);
  origen.val(destino_val);
}

// ---------------------------------------
function comprobarLogin() {
  var user = $("#login_Cliente").val();
  var password = $("#login_Password").val();

  fetch("http://localhost/TFG/proyectoTFG/server/public/api/loginCliente", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      user: user,
      password: password,
    }),
  }).then((res) => {
    if (res.status == 200) {
      var mensaje_correcto = $("#mensaje_correcto");
      var gif_cargando = $("#gif_cargando");
      var mensaje_error = $("#mensaje_error");
      res.json().then((data) => {
        if (data.correcto) {
          mensaje_correcto.addClass("alert alert-success");
          mensaje_correcto.text(data.correcto.toString());
          gif_cargando.removeClass("d-none");

          localStorage.setItem("nombre", data.nombre);

          mensaje_error.text("");
          mensaje_error.removeClass("alert alert-danger");
          setTimeout(() => {
            window.location.href =
              "http://localhost/TFG/proyectoTFG/client/archivos/index.html";
          }, 3000);
        } else {
          mensaje_error.addClass("alert alert-danger");
          mensaje_error.text(data.error, toString());
          mensaje_correcto.text("");
          mensaje_correcto.removeClass("alert alert-success");
        }
      });
    } else {
      alert("Error en el servidor");
    }
  });
}

// ---------------------------------------
// Función para calcular la distancia entre dos puntos geográficos
function distanciaEntrePuntos(latitud1, longitud1, latitud2, longitud2) {
  const radioTierra = 6371; // Radio de la Tierra en kilómetros
  var tiempo = 0;

  // Convertir grados a radianes
  const radianesLatitud1 = latitud1 * (Math.PI / 180);
  const radianesLongitud1 = longitud1 * (Math.PI / 180);
  const radianesLatitud2 = latitud2 * (Math.PI / 180);
  const radianesLongitud2 = longitud2 * (Math.PI / 180);

  // Calcular la diferencia de latitud y longitud
  const deltaLatitud = radianesLatitud2 - radianesLatitud1;
  const deltaLongitud = radianesLongitud2 - radianesLongitud1;

  // Calcular la distancia utilizando la fórmula de Haversine
  const a =
    Math.sin(deltaLatitud / 2) * Math.sin(deltaLatitud / 2) +
    Math.cos(radianesLatitud1) *
      Math.cos(radianesLatitud2) *
      Math.sin(deltaLongitud / 2) *
      Math.sin(deltaLongitud / 2);
  const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
  const distancia = radioTierra * c;
  if (distancia < 500) {
    tiempo = distancia / 450; // La distancia en horas si la distancia es menor a 500 km
  } else {
    tiempo = distancia / 750; // La distancia en horas si la distancia es mayor a 500 km
  }
  const horas = Math.floor(tiempo); // Obtener la parte entera de las horas
  const minutos = Math.ceil((tiempo - horas) * 60); // Calcular los minutos restantes
  return [horas, minutos];
}

// ---------------------------------------
// Función para ir hacia arriba
function irHaciaArriba() {
  window.scrollTo({ top: 0, behavior: "smooth" });
}

// ---------------------------------------
// Función para obtener coordernadas
function obtenerIntervalo(coordenadasOrigen, coordenadasDestino) {
  var latitudOrigen = "";
  var longitudOrigen = "";
  var latitudDestino = "";
  var longitudDestino = "";

  // Obtener las coordenadas de origen y destino

  // Coordenadas de origen
  coordenadasOrigen = coordenadasOrigen.replace("POINT (", "");
  coordenadasOrigen = coordenadasOrigen.substring(
    0,
    coordenadasOrigen.length - 1
  );
  var espacio = coordenadasOrigen.indexOf(" ");
  latitudOrigen = parseFloat(coordenadasOrigen.substring(0, espacio));
  longitudOrigen = parseFloat(coordenadasOrigen.substring(espacio + 1));

  // Coordenadas de destino
  coordenadasDestino = coordenadasDestino.replace("POINT (", "");
  coordenadasDestino = coordenadasDestino.substring(
    0,
    coordenadasDestino.length - 1
  );
  espacio = coordenadasDestino.indexOf(" ");
  latitudDestino = parseFloat(coordenadasDestino.substring(0, espacio));
  longitudDestino = parseFloat(coordenadasDestino.substring(espacio + 1));

  var tiempo = distanciaEntrePuntos(
    latitudOrigen,
    longitudOrigen,
    latitudDestino,
    longitudDestino
  );

  return tiempo;
}

// ---------------------------------------
// Función para formatear fecha
function formatearFecha(fecha) {
  fecha = fecha.split("-");
  var dia = fecha[2];
  var mes = fecha[1];
  var año = fecha[0];

  return dia + "/" + mes + "/" + año;
}

// ---------------------------------------
// Función para calcular intervalo de tiempo
function calcularIntervaloFechas(fechaHoraSalida, fechaHoraLlegada) {
  fechaHoraSalida = fechaHoraSalida.split(" ");
  // Fecha y hora de salida
  var fechaSalida = fechaHoraSalida[0].split("-");
  var timeSalida = fechaHoraSalida[1].split(":");
  var diaSalida = fechaSalida[2];
  var mesSalida = fechaSalida[1];
  var anyoSalida = fechaSalida[0];
  var horaSalida = timeSalida[0];
  var minutosSalida = timeSalida[1];

  // Fecha y hora de llegada
  fechaHoraLlegada = fechaHoraLlegada.split(" ");
  var fechaLlegada = fechaHoraLlegada[0].split("-");
  var timeLlegada = fechaHoraLlegada[1].split(":");
  var diaLlegada = fechaLlegada[2];
  var mesLlegada = fechaLlegada[1];
  var anyoLlegada = fechaLlegada[0];
  var horaLlegada = timeLlegada[0];
  var minutosLlegada = timeLlegada[1];

  var fechaSalidaFormat = new Date(
    anyoSalida,
    mesSalida,
    diaSalida,
    horaSalida,
    minutosSalida
  );
  var fechaLlegadaFormat = new Date(
    anyoLlegada,
    mesLlegada,
    diaLlegada,
    horaLlegada,
    minutosLlegada
  );
  var intervalo = fechaLlegadaFormat - fechaSalidaFormat;
  var horas = Math.floor(intervalo / 3600000);
  var minutos = Math.floor((intervalo - horas * 3600000) / 60000);
  return [horas, minutos];
}

// ---------------------------------------
// Función para calcular el precio del vuelo
function calcularPrecioVuelo(intervalo) {
  const precioBase = Math.round(Math.random() * 5 + 10);
  var precio = 0;
  var horas = intervalo[0];
  var minutos = intervalo[1];
  minutos += horas * 60;
  if (minutos < 90) {
    precio = Math.round(Math.random() * 4.5 + 1) * precioBase;
  }
  if (minutos >= 90 && minutos < 180) {
    precio = Math.round(Math.random() * 12 + 8) * precioBase;
  }
  if (minutos >= 180 && minutos < 300) {
    precio = Math.round(Math.random() * 30 + 15) * precioBase;
  }
  if (minutos >= 300 && minutos < 600) {
    precio = Math.round(Math.random() * 50 + 40) * precioBase;
  }
  if (minutos >= 600) {
    precio = Math.round(Math.random() * 140 + 60) * precioBase;
  }
  return precio;
}

// ---------------------------------------
// Función para cargar ciudades
function cargarCiudades() {
  return new Promise((resolve, reject) => {
    if (!datosCargados) {
      fetch("https://api.npoint.io/0ae89dcddb751bee38ef").then((res) => {
        res.json().then((datos) => {
          datos.forEach((ciudad) => {
            ciudades[ciudad.iata] = ciudad.city;
          });
          datosCargados = true;
          resolve();
        });
      });
    } else {
      resolve();
    }
  });
}

function pintarAsientos() {
  const totalAsientos = 90;
  const asientosPorFila = 6;
  var contador = 0;
  const letras = ["A", "B", "C", "D", "E", "F"];
  var section = $(".asientos");
  var tabla = `
  <table class="table w-50">
    <thead>
      <tr>
        <th class="text-center bg-transparent border-0 fs-3" scope="col">A</th>
        <th class="text-center bg-transparent border-0 fs-3" scope="col">B</th>
        <th class="text-center bg-transparent border-0 fs-3" scope="col">C</th>
        <th class="bg-transparent border-0"></th>
        <th class="text-center bg-transparent border-0 fs-3" scope="col">D</th>
        <th class="text-center bg-transparent border-0 fs-3" scope="col">E</th>
        <th class="text-center bg-transparent border-0 fs-3" scope="col">F</th>
      </tr>
    </thead>
  <tbody>`;

  for (let i = 1; i <= totalAsientos; i++) {
    if (i % asientosPorFila == 1) {
      tabla += `<tr>`;
    }
    if (i % 6 == 4) {
      contador++;
      tabla += `<td class="text-center bg-transparent border-0 fs-3">${contador}</td>`;
    }
    if (i <= 36) {
      tabla += `<td class="text-center bg-transparent border-0" title="${
        letras[(i - 1) % 6]
      }${i}"><button class="btn btn-warning p-4 my-1"></button></td>`;
    } else if (i > 36 && i <= 60) {
      tabla += `<td class="text-center bg-transparent border-0" title="${
        letras[(i - 1) % 6]
      }${i}"><button style="background-color:#c343ff" class="btn p-4 my-1"></button></td>`;
    } else {
      tabla += `<td class="text-center bg-transparent border-0" title="${
        letras[(i - 1) % 6]
      }${i}"><button class="btn btn-primary p-4 my-1"></button></td>`;
    }
    if (i % asientosPorFila == 0) {
      tabla += `</tr>`;
    }
  }
  tabla += `</tbody>
          </table>`;
  section.append(tabla);
}
