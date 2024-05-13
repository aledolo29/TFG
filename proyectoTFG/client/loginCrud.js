addEventListener("DOMContentLoaded", comprobarSesion);

function comprobarSesion() {
  $.ajax({
    url: "server/clases/check_session.php",
    type: "GET",
    success: function (data) {
      if (data.trim() == "1") {
        window.location.href = "crud.html";
      }
    },
  });
}
