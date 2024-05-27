$(document).ready(function () {
  var idCliente = localStorage.getItem("idCliente");
  fetch("http://localhost/TFG/proyectoTFG/server/public/api/obtenerBilletes", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ idCliente: idCliente }),
  }).then((response) => {
    if (response.ok) {
      response.json().then((data) => {
        if (data.length == 0) {
          $(".billetes__lista").append("<div>No tienes billetes</div>");
        } else {
          
        }
      });
    }
  });
});
