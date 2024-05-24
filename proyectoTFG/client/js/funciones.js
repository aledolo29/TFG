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
          localStorage.setItem("idCliente", data.idCliente);

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

// ---------------------------------------
// Función tiempo de espera
function tiempoEspera() {}
