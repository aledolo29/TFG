// Ocultar reseñas
$("#resenas_ocultas").hide();

// Vuelos Recomendados
$(".enlace_vueloRecomendado").click(function (e) {
  e.preventDefault();
  var href = $(this).attr("href").split("=");
  var ciudad = href[1];
  var aeropuertos = [];

  fetch("https://api.npoint.io/0ae89dcddb751bee38ef").then((res) => {
    res.json().then((datos) => {
      datos.forEach((c) => {
        if (c.city == ciudad) {
          aeropuertos.push({ nombre: ciudad + " (" + c.iata + ")", id: c.id });
        }
      });
      var aeropuertoRecomendado =
        aeropuertos[Math.floor(Math.random() * aeropuertos.length)];
      sessionStorage.setItem(
        "aeropuertoRecomendado",
        aeropuertoRecomendado.nombre
      );
      sessionStorage.setItem(
        "id_aeropuertoRecomendado",
        aeropuertoRecomendado.id
      );
      window.location.href = "formularioBuscarVuelo.html";
    });
  });
});

$(document).ready(function () {
  let params = new URLSearchParams(window.location.search);
  let avisoSesion = params.get("avisoSesion");

  if (avisoSesion) {
    $("#modal_login").modal("show");
    $("#mensaje_aviso").addClass("alert alert-primary");
    $("#mensaje_aviso").text("Inicia sesión para continuar");
  }

  // Fecha minima formulario busca vuelo
  var fecha = $("#fecha");

  var date = new Date();
  var year = date.getFullYear();
  var month = (date.getMonth() + 1).toString().padStart(2, "0");
  var day = date.getDate().toString().padStart(2, "0");

  var fechaFormateada = year + "-" + month + "-" + day;
  fecha.attr("min", fechaFormateada);
});

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
var buscarVueloBtn = $("#buscaVuelos_btn");
buscarVueloBtn.click(function (e) {
  e.preventDefault();
  $("#mensaje_error_formulario").removeClass("alert alert-danger");
  $("#mensaje_error_formulario").text("");
  var id_aeropuerto_origen = $("#id_aeropuerto_origen").val();
  var id_aeropuerto_destino = $("#id_aeropuerto_destino").val();
  var fecha = $("#fecha").val();
  var pasajeros = $("#pasajeros").val();

  if (
    id_aeropuerto_origen == "" ||
    id_aeropuerto_destino == "" ||
    fecha == "" ||
    pasajeros == ""
  ) {
    $("#mensaje_error_formulario").addClass("alert alert-danger");
    $("#mensaje_error_formulario").text("Debes rellenar todos los campos");
  } else {
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
          "http://localhost/TFG/proyectoTFG/server/public/api/buscarVuelo",
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
                if (!("precio" in vuelo)) {
                  var intervalo = calcularIntervaloFechas(
                    vuelo.vuelo_Fecha_Hora_Salida,
                    vuelo.vuelo_Fecha_Hora_Llegada
                  );
                  vuelo.precio = calcularPrecioVuelo(intervalo);
                }
              });
              localStorage.setItem("vuelos", JSON.stringify(data));
              window.location.href =
                "http://localhost/TFG/proyectoTFG/client/archivos/listadoVuelos.html";
            });
          } else {
            alert("Error en el servidor");
          }
        });
      });
    });
  }
});

// ---------------------------------------
// Vlidar fecha minima
$("#fecha").change(function () {
  var fecha = $("#fecha").val();
  var date = new Date(fecha);
  var dateActual = new Date();

  var yearMore = dateActual.getFullYear() + 1;
  var monthMore = (dateActual.getMonth() + 1).toString().padStart(2, "0");
  var dayMore = dateActual.getDate().toString().padStart(2, "0");
  var fechaMoreFormateada = yearMore + "-" + monthMore + "-" + dayMore;

  if (fecha > fechaMoreFormateada) {
    $("#fecha").val(fechaMoreFormateada);
  }

  if (date < dateActual) {
    var year = dateActual.getFullYear();
    var month = (dateActual.getMonth() + 1).toString().padStart(2, "0");
    var day = dateActual.getDate().toString().padStart(2, "0");
    var fechaFormateada = year + "-" + month + "-" + day;

    $("#fecha").val(fechaFormateada);
  }
});

// ---------------------------------------
// Validar numero de pasajeros
$("#numPasajeros").change(function () {
  if($("#numPasajeros").val() < 1){
    $("#numPasajeros").val(1);
  }
  if($("#numPasajeros").val() > 9){
    $("#numPasajeros").val(9);
  }
});