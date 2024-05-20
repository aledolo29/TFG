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
  return horas + " horas y " + minutos + " minutos";
}

// Función para ir hacia arriba
function irHaciaArriba() {
  window.scrollTo({ top: 0, behavior: "smooth" });
}

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
  var password2 = $("#repetir_password").val();
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

function cambiarOrigenDestino(e) {
  e.preventDefault();
  var origen = $("#aeropuerto_origen");
  var destino = $("#aeropuerto_destino");
  var origen_val = origen.val();
  var destino_val = destino.val();
  destino.val(origen_val);
  origen.val(destino_val);
}

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
            `<li class='dropdown-item dropdown__aeropuertos__item __origen fw-bold fs-4'></li>`
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
          "<li class='dropdown-item dropdown__aeropuertos__item__destino fs-4'></li>"
        );
        opcion.html("No se han encontrado resultados de destino para " + valor);
        lista.append(opcion);
      }
    });
  });
});

// Seleccionar aeropuerto origen
$(".lista_aeropuertos_origen").on("click", "li", function () {
  var valor = $(this).text();
  posParentesis = valor.indexOf(")");
  valor = valor.substring(0, posParentesis + 1);
  $(".aeropuerto_origen").val(valor);
  $(".lista_aeropuertos_origen").hide();
  $(".id_aeropuerto_origen").val($(this).val()); // Guardar el id del aeropuerto origen (oculto)
});

// Seleccionar aeropuerto destino
$(".lista_aeropuertos_destino").on("click", "li", function () {
  var valor = $(this).text();
  posParentesis = valor.indexOf(")");
  valor = valor.substring(0, posParentesis + 1);
  $(".aeropuerto_destino").val(valor);
  $(".lista_aeropuertos_destino").hide();
  $(".id_aeropuerto_destino").val($(this).val()); // Guardar el id del aeropuerto destino (oculto)
});

// BUSQUEDA VUELOS
// Buscar vuelos
var buscarVueloBtn = $(".buscaVuelos__btn");
buscarVueloBtn.click(function (e) {
  e.preventDefault();
  var latitudOrigen;
  var longitudOrigen;
  var latitudDestino;
  var longitudDestino;
  var origen = $(".id_aeropuerto_origen").val();
  var destino = $(".id_aeropuerto_destino").val();
  fetch("https://api.npoint.io/0ae89dcddb751bee38ef").then((res) => {
    res.json().then((data) => {
      data.forEach((aeropuerto) => {
        // Origen
        if (aeropuerto.id == origen) {
          var coordenadas = aeropuerto.coordinates_wkt;
          coordenadas = coordenadas.replace("POINT (", "");
          coordenadas = coordenadas.substring(0, coordenadas.length - 1);
          var espacio = coordenadas.indexOf(" ");
          latitudOrigen = parseFloat(coordenadas.substring(0, espacio));
          longitudOrigen = parseFloat(coordenadas.substring(espacio + 1));
        }
        // Destino
        if (aeropuerto.id == destino) {
          var coordenadas = aeropuerto.coordinates_wkt;
          coordenadas = coordenadas.replace("POINT (", "");
          coordenadas = coordenadas.substring(0, coordenadas.length - 1);
          var espacio = coordenadas.indexOf(" ");
          latitudDestino = parseFloat(coordenadas.substring(0, espacio));
          longitudDestino = parseFloat(coordenadas.substring(espacio + 1));
        }
      });
      var tiempo = distanciaEntrePuntos(
        latitudOrigen,
        longitudOrigen,
        latitudDestino,
        longitudDestino
      );
      console.log(tiempo);
    });
  });
});

// Buscar vuelos
// Vuelo ida
var buscarVueloBtn_Ida = $("#buscaVuelos_btn_ida");
buscarVueloBtn.click(function (e) {
  e.preventDefault();
  var origen = $("#id_aeropuerto_origen_ida").val();
  var destino = $("#id_aeropuerto_destino_ida").val();
  var fecha = $("#fecha_ida").val();
  var pasajeros = $("#pasajeros_ida").val();
  const vuelos = [];
  let minutos = [];
  let horasMañana = [];
  for (let i = 0; i < 60; i++) {
    minutos.push(i);
  }

  for (let i = 0; i < 2; i++) {
    horasMañana.push(Math.random() * 7) + 6;
  }
});
