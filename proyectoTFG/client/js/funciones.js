// Para saber si ha iniciado sesión
$(document).ready(function () {
  if (localStorage.getItem("nombre") != null) {
    $("#btn_login_text").text("Hola " + localStorage.getItem("nombre"));
  } else {
    $("#btn_login_text").text("Iniciar sesión");
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
  var origen = $("#origen");
  var destino = $("#destino");
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
// Aeropuerto ida
$("#aeropuerto_ida").keyup(function () {
  var valor = $(this).val().toLowerCase();
  var lista = $("#lista_aeropuertos_ida");
  fetch("https://api.npoint.io/0ae89dcddb751bee38ef").then((res) => {
    res.json().then((data) => {
      lista.empty();
      lista.show();
      data.forEach((aeropuerto) => {
        if (aeropuerto.city.toLowerCase().startsWith(valor)) {
          var opcion = $(
            "<li class='dropdown-item dropdown__aeropuertos__item fw-bold fs-4'></li>"
          );
          opcion.html(
            aeropuerto.city +
              ` (${aeropuerto.iata})<br><span>${aeropuerto.country}</span>`
          );
          opcion.val(aeropuerto.id);
          lista.append(opcion);
        }
      });
    });
  });
});
