// REGISTRO DE CLIENTE
// boton formulario Registro
$("#btnRegistro").click(function () {
  var nombre = primeraLetraMayuscula($("#cliente_Nombre").val());
  var apellidos = primeraLetraMayuscula($("#cliente_Apellidos").val());
  var usuario = $("#cliente_Usuario").val();
  var password = $("#cliente_Password").val();
  var password2 = $("#repetir_password").val();
  var dni = $("#cliente_DNI").val();
  var correo = $("#cliente_Correo").val();
  var telefono = $("#cliente_Telefono").val();

  if (
    nombre == "" ||
    apellidos == "" ||
    usuario == "" ||
    password == "" ||
    password2 == "" ||
    dni == "" ||
    correo == "" ||
    telefono == ""
  ) {
    $("#mensaje_error").addClass("alert alert-danger");
    $("#mensaje_error").text("Rellene todos los campos");
  } else {
    if (password == password2) {
      fetch(
        "https://ruizgijon.ddns.net/domingueza/TFG/proyectoTFG/server/public/api/registroCliente",
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
          var gif_cargando = $("#gif_cargando");

          res.json().then((data) => {
            if (data.correcto) {
              localStorage.setItem("nombre", data.cliente.cliente_Nombre);
              localStorage.setItem("apellidos", data.cliente.cliente_Id);
              mensaje_correcto.addClass("alert alert-success");
              mensaje_correcto.text(data.correcto.toString());
              gif_cargando.removeClass("d-none");
              mensaje_error.text("");
              mensaje_error.removeClass("alert alert-danger");
              setTimeout(() => {
                window.location.href =
                  "https://ruizgijon.ddns.net/domingueza/TFG/proyectoTFG/client/archivos/index.html";
              }, 4000);
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
  }
});
