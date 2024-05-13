// https://api.npoint.io/5d648ad190ba517a6c77
addEventListener("load", inicio);
var div = document.getElementById("aeropuertos");
function inicio() {
  var boton = document.getElementById("buscar");
  boton.addEventListener("keyup", buscar);
  function buscar() {
    var peticion = new XMLHttpRequest();
    peticion.open("GET", "https://api.npoint.io/5d648ad190ba517a6c77", true);
    peticion.onload = function () {
      // Verificar si la solicitud se completó correctamente (estado 200)
      if (peticion.status >= 200 && peticion.status < 400) {
        
      } else {
        console.error("Error al realizar la solicitud: " + peticion.status);
      }
    };
    peticion.send();
  }
}
