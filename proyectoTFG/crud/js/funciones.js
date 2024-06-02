addEventListener("load", function () {
  var boton_eye = document.getElementsByClassName("mostrar_password");
  var texto_password = document.getElementsByClassName("password_texto");
  for (let i = 0; i < boton_eye.length; i++) {
    boton_eye[i].addEventListener("click", function (e) {
      e.preventDefault();
      if (this.classList == "bi bi-eye mostrar_password") {
        this.classList = "bi bi-eye-slash mostrar_password";
        texto_password[i].type = "text";
      } else {
        this.classList = "bi bi-eye mostrar_password";
        texto_password[i].type = "password";
      }
    });
  }
});

// Aeropuerto origen
var aeropuertos_origen = $("#aeropuerto_origen");
aeropuertos_origen.change(function () {
  var valor = $(this).val().toLowerCase();
  if (valor.length == 0) {
    aeropuertos_origen.val("");
    return;
  }
});
$("#aeropuerto_origen").keyup(function () {
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
var aeropuertos_destino = $("#aeropuerto_destino");
aeropuertos_destino.change(function () {
  var valor = $(this).val().toLowerCase();
  if (valor.length == 0) {
    aeropuertos_destino.val("");
    return;
  }
});

$("#aeropuerto_destino").keyup(function () {
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
  $("#aeropuerto_origen").val(text);
  $(".lista_aeropuertos_origen").hide();
  $(".id_aeropuerto_origen").val($(this).val()); // Guardar el id del aeropuerto origen (oculto)
});

// Seleccionar aeropuerto destino
$(".lista_aeropuertos_destino").on("click", "li", function () {
  var text = $(this).text();
  posParentesis = text.indexOf(")");
  text = text.substring(0, posParentesis + 1);
  $("#aeropuerto_destino").val(text);
  $(".lista_aeropuertos_destino").hide();
  $(".id_aeropuerto_destino").val($(this).val()); // Guardar el id del aeropuerto destino (oculto)
});
