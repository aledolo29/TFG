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
