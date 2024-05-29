// Ocultar reseñas
$("#resenas_ocultas").hide();

// Vuelos Recomendados
$(".enlace_vueloRecomendado").click(function (e) {
  e.preventDefault();
  var href = $(this).attr("href").split("=");
  var ciudad = href[1];
  var aeropuertos = [];

  fetch("https://api.npoint.io/0ae89dcddb751bee38ef").then((res) => {
    res.json().then((datos) => {
      datos.forEach((c) => {
        if (c.city == ciudad) {
          aeropuertos.push({ nombre: ciudad + " (" + c.iata + ")", id: c.id });
        }
      });
      var aeropuertoRecomendado =
        aeropuertos[Math.floor(Math.random() * aeropuertos.length)];
      sessionStorage.setItem(
        "aeropuertoRecomendado",
        aeropuertoRecomendado.nombre
      );
      sessionStorage.setItem(
        "id_aeropuertoRecomendado",
        aeropuertoRecomendado.id
      );
      window.location.href = "formularioBuscarVuelo.html";
    });
  });
});
