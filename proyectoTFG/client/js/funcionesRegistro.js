// REGISTRO DE CLIENTE
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
