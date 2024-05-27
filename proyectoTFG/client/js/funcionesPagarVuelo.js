$(document).ready(function () {
  var asientos = sessionStorage.getItem("asientosSeleccionados");
  var vuelo = sessionStorage.getItem("vueloSeleccionado");
  if (asientos == null || vuelo == null) {
    window.location.href = "index.html";
  }

  var nombre = "",
    apellido = "",
    email = "",
    telefono = "",
    precio = 0;

  var billete = $("#billete");
  var idCliente = localStorage.getItem("idCliente");
  if (idCliente == null) {
    window.location.href = "index.html";
  }
  cargarCiudades().then(() => {
    fetch("http://localhost/TFG/proyectoTFG/server/public/api/obtenerCliente", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        idCliente: idCliente,
      }),
    }).then((response) => {
      response.json().then((data) => {
        idCliente = data.cliente.cliente_Id;
        nombre = data.cliente.cliente_Nombre;
        apellido = data.cliente.cliente_Apellidos;
        email = data.cliente.cliente_Correo;
        telefono = data.cliente.cliente_Telefono;
        vuelo = JSON.parse(decodeURIComponent(vuelo));
        asientos = JSON.parse(asientos);

        asientos.forEach((asiento) => {
          var num = parseInt(asiento.substring(1));
          if (num >= 1 && num <= 6) {
            precio += vuelo.precio * 1.5;
          } else if (num >= 7 && num <= 10) {
            precio += vuelo.precio * 3;
          } else {
            precio += vuelo.precio;
          }
        });

        var fechaHora = vuelo.vuelo_Fecha_Hora_Salida.split(" ");
        var fecha = formatearFecha(fechaHora[0]);
        var hora = fechaHora[1].substring(0, 5);

        var contenido = `
    <div class="card rounded-4 mt-5">
        <div class="card-header d-flex flex-column justify-content-center align-items-center p-4 bg-white rounded-top-4">
            <img class="img-fluid pagarVuelo__logo" src="../../assets/media/logo_azul.png" alt="avion" style="width: 70px; height: 70px;">
            <h3 class="text-center text-primary pagarVuelo__titulo__card">INTERSTELLAR AIRLINES</h3>
        </div>
        <div class="card-body border-top border-primary">
            <div class="d-flex justify-content-between">
                <p class="text-primary">Nombre:</p>
                <p>${nombre}</p>
            </div>
            <div class="d-flex justify-content-between">
                <p class="text-primary">Apellidos:</p>
                <p>${apellido}</p>
            </div>
            <div class="d-flex justify-content-between">
                <p class="text-primary">Origen:</p>
                <p>${ciudades[vuelo.vuelo_AeropuertoSalida]} (${
          vuelo.vuelo_AeropuertoSalida
        })</p>
            </div>
            <div class="d-flex justify-content-between">
                <p class="text-primary">Destino:</p>
                <p>${ciudades[vuelo.vuelo_AeropuertoLlegada]} (${
          vuelo.vuelo_AeropuertoLlegada
        })</p>
            </div>
            <div class="d-flex justify-content-between">
              <p class="text-primary">Asiento/s:</p>
              <p>${asientos}</p>
            </div>
            <div class="d-flex justify-content-between">
                <p class="text-primary">Fecha:</p>
                <p>${fecha}</p>
            </div>
            <div class="d-flex justify-content-between">
                <p class="text-primary">Hora:</p>
                <p>${hora}</p>
            </div>
            <div class="d-flex justify-content-between">
                <p class="text-primary">Precio:</p>
                <p>${precio}€</p>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-end mt-3"><button class="pagarVuelo__btn px-5 py-3 fs-4 fw-bold border-0" id="pagar">Realizar pago</button></div>`;

        billete.append(contenido);

        $(".pagarVuelo__btn").click(function () {
          var url =
            "http://localhost/TFG/proyectoTFG/server/public/checkout?idCliente=" +
            idCliente +
            "&cliente=" +
            nombre +
            " " +
            apellido +
            "&origen=" +
            ciudades[vuelo.vuelo_AeropuertoSalida] +
            "(" +
            vuelo.vuelo_AeropuertoSalida +
            ")&destino=" +
            ciudades[vuelo.vuelo_AeropuertoLlegada] +
            "(" +
            vuelo.vuelo_AeropuertoLlegada +
            ")&asientos=" +
            asientos +
            "&fecha=" +
            fecha +
            "&hora=" +
            hora +
            "&precio=" +
            precio;

          window.location.href = url;
        });
      });
    });
  });
});
