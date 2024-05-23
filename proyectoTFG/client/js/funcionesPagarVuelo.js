$(document).ready(function () {
  var nombre = "",
    apellido = "",
    email = "",
    telefono = "";

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
        nombre = data.cliente.cliente_Nombre;
        apellido = data.cliente.cliente_Apellidos;
        email = data.cliente.cliente_Correo;
        telefono = data.cliente.cliente_Telefono;

        var vuelo = localStorage.getItem("vueloSeleccionado");
        if (vuelo == null) {
          window.location.href = "index.html";
        }
        vuelo = vuelo = decodeURIComponent(JSON.parse(vuelo));
        vuelo = JSON.parse(vuelo);
        var fechaHora = vuelo.vuelo_Fecha_Hora_Salida.split(" ");
        var fecha = formatearFecha(fechaHora[0]);
        var hora = fechaHora[1].substring(0, 5);
        var contenido = `
    <div class="card rounded-4 mt-5">
        <div class="card-header d-flex flex-column justify-content-center align-items-center p-4 bg-white rounded-top-4">
            <img class="img-fluid pagarVuelo__logo" src="../../assets/media/logo_azul.png" alt="avion" style="width: 70px; height: 70px;">
            <h3 class="text-center text-primary pagarVuelo__card__titulo">DETALLES DEL BILLETE</h3>
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
                <p class="text-primary">Fecha:</p>
                <p>${fecha}</p>
            </div>
            <div class="d-flex justify-content-between">
                <p class="text-primary">Hora:</p>
                <p>${hora}</p>
            </div>
            <div class="d-flex justify-content-between">
                <p class="text-primary">Precio:</p>
                <p>${vuelo.precio}€</p>
            </div>
        </div>
    </div>
      `;

        billete.append(contenido);
      });
    });
  });
});
