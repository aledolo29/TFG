// Variables globales para la carga de ciudades
var ciudades = {};
var datosCargados = false;

// EVENTOS 🎉
// Para saber si ha iniciado sesión
$(document).ready(function () {
  cargarComponente("header").then(() => {
    if (localStorage.getItem("nombre") != null) {
      $("#btn_login_component").html(
        `<a id="btn_login" class="nav-link text-white header__btn__sesion px-3" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <span id="btn_login_text">Hola ${localStorage.getItem(
              "nombre"
            )}</span>
            <i class="bi bi-person-circle fs-2 ps-2"></i
          ></a>
          <ul class="dropdown-menu dropdown-menu-end dropdown_logout rounded-4">
          <li class="p-2"><a class="dropdown-item fs-4 fw-bold d-flex justify-content-between align-items-center" href="listadoBilletes.html"><img src="../../../build/assets/media/airplane-ticket.png">Mis Billetes</a></li>
            <li class="p-2"><a class="dropdown-item fs-4 fw-bold d-flex justify-content-between align-items-center" href="#" onclick="cerrarSesion()"><i class="bi bi-box-arrow-left me-4 fs-2"></i>Cerrar Sesión</a></li>
          </ul>`
      );

      $("#enlace_registro").hide();
    } else {
      $("#btn_login_component").html(
        `<a
        id="btn_login"
        class="nav-link text-white header__btn__sesion px-3"
        href="#" data-bs-toggle="modal"
        data-bs-target="#modal_login">
        <span id="btn_login_text">Iniciar Sesión</span>
        <i class="bi bi-person-circle fs-2 ps-2"></i
      ></a>`
      );
    }
  });
  cargarComponente("footer");
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

// Comprobar recordatorio de contraseña
$("#recuperar_contrasena").click(function (e) {
  e.preventDefault();
  var email = $("#email_recuperar_contrasena").val();
  fetch(
    "http://localhost/TFG/proyectoTFG/server/public/api/recuperarContrasena",
    {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        email: email,
      }),
    }
  ).then((res) => {
    if (res.status == 200) {
      res.json().then((data) => {
        if (data.correcto) {
          $("#mensaje_error_recuperar").text("");
          $("#mensaje_error_recuperar").removeClass("alert alert-danger");

          $("#mensaje_correcto_recuperar").addClass("alert alert-success");
          $("#mensaje_correcto_recuperar").text(
            "En breves le llegará un correo con un recordatorio de su contraseña"
          );
        } else {
          $("#mensaje_correcto_recuperar").text("");
          $("#mensaje_correcto_recuperar").removeClass("alert alert-success");

          $("#mensaje_error_recuperar").addClass("alert alert-danger");
          $("#mensaje_error_recuperar").text(
            "No existe ningún usuario con ese email"
          );
        }
      });
    } else {
      alert("Error en el servidor");
    }
  });
});

// FUNCIONES 💻

// ---------------------------------------
// Función seguridad
function seguridad() {
  if (localStorage.getItem("nombre") == null) {
    window.location.href =
      "http://localhost/TFG/proyectoTFG/client/archivos/index.html?avisoSesion=true";
  }
}

// ---------------------------------------
function cargarComponente(nombre) {
  return new Promise((resolve, reject) => {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "components/" + nombre + ".html", true);
    xhr.onreadystatechange = function () {
      if (xhr.readyState == 4 && xhr.status == 200) {
        document.getElementsByTagName(nombre)[0].innerHTML = xhr.responseText;
        resolve();
      } else if (xhr.readyState == 4) {
        reject();
      }
    };
    xhr.send();
  });
}

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
          localStorage.setItem("idCliente", data.idCliente);

          mensaje_error.text("");
          mensaje_error.removeClass("alert alert-danger");
          setTimeout(() => {
            window.location.reload();
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
  // Convertir las cadenas de fecha y hora a objetos Date
  var fechaSalidaFormat = new Date(fechaHoraSalida);
  var fechaLlegadaFormat = new Date(fechaHoraLlegada);

  // Calcular el intervalo en milisegundos
  var intervalo = fechaLlegadaFormat - fechaSalidaFormat;

  // Convertir el intervalo a horas y minutos
  var horas = Math.floor(intervalo / 3600000);
  var minutos = Math.floor((intervalo % 3600000) / 60000);

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
    }else{
      resolve();
    }
  });
}

// ---------------------------------------
// Función tiempo de espera
function tiempoEspera() {}

// ---------------------------------------
// Función para cerrar sesión
function cerrarSesion() {
  localStorage.clear();
  sessionStorage.clear();
  window.location.reload();
}

// ---------------------------------------
// Función primera letra mayúscula
function primeraLetraMayuscula(cadena) {
  return cadena.charAt(0).toUpperCase() + cadena.slice(1);
}
