// ---------------------------------------
// Rellenar datos de pasajero
$(document).ready(function () {
  pintarAsientos();
  var asiento = $(".btn_asiento");
  asiento.click(function (e) {
    e.preventDefault();
    if ($(this).css("background-color") == "rgb(195, 67, 255)") {
      $(this).css("background-color", "");
      $(this).css("border-color", "");
      $(this).addClass("btn-success");
      return;
    }
    if ($(this).hasClass("btn-success")) {
      $(this).css("background-color", "rgb(195, 67, 255)");
      $(this).css("border-color", "rgb(195, 67, 255)");
      $(this).removeClass("btn-success");
      return;
    }
    if ($(this).css("background-color") == "rgb(0, 128, 0)") {
      $(this).css("background-color", "");
      $(this).css("border-color", "");
      return;
    } else {
      $(this).css("background-color", "green");
      $(this).css("border-color", "green");
      return;
    }
  });
});
