$(document).ready(function () {
  seguridad();
  let params = new URLSearchParams(window.location.search);
  let eliminado = params.get("eliminado");

  if (eliminado) {
    $("#mensaje_eliminado").addClass(
      "alert alert-success w-25 m-auto text-center fs-4"
    );
    $("#mensaje_eliminado").text("Billete cancelado correctamente");

    setTimeout(function () {
      $("#mensaje_eliminado").removeClass(
        "alert alert-success w-25 m-auto text-center fs-4"
      );
      $("#mensaje_eliminado").text("");
    }, 3000);
  }

  var idCliente = localStorage.getItem("idCliente");
  // Cargamos las ciudades
  cargarCiudades().then(() => {
    fetch(
      "http://localhost/TFG/proyectoTFG/server/public/api/obtenerBilletes",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ idCliente: idCliente }),
      }
    ).then((response) => {
      if (response.ok) {
        response.json().then((dataBilletes) => {
          if (dataBilletes.length == 0) {
            $(".listadoBilletes__lista").append(
              `<div class='m-auto d-flex flex-column justify-content-center align-items-center w-50'>
              <img class="img-fluid" src="../../../build/assets/media/no-flights-2.png">
              <h2 class='text-center fs-2'>No tienes billetes comprados</h2>
              <a href="formularioBuscarVuelo.html" class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover fs-4 mt-3">¡Empieza a buscar ya!</a>
              </div>`
            );
          } else {
            for (let i = 0; i < dataBilletes.length; i++) {
              fetch(
                "http://localhost/TFG/proyectoTFG/server/public/api/obtenerVuelo",
                {
                  method: "POST",
                  headers: {
                    "Content-Type": "application/json",
                  },
                  body: JSON.stringify({
                    vuelo_Id: dataBilletes[i].billete_vuelo_IdFK,
                  }),
                }
              ).then((response) => {
                if (response.ok) {
                  response.json().then((dataVuelo) => {
                    let intervalo = calcularIntervaloFechas(
                      dataVuelo.vuelo_Fecha_Hora_Salida,
                      dataVuelo.vuelo_Fecha_Hora_Llegada
                    );

                    let fechaSalida =
                      dataVuelo.vuelo_Fecha_Hora_Salida.split(" ")[0];
                    let fechaLlegada =
                      dataVuelo.vuelo_Fecha_Hora_Llegada.split(" ")[0];
                    let horaSalida = dataVuelo.vuelo_Fecha_Hora_Salida
                      .split(" ")[1]
                      .substr(0, 5);
                    let horaLlegada = dataVuelo.vuelo_Fecha_Hora_Llegada
                      .split(" ")[1]
                      .substr(0, 5);

                    var billete = "";
                    billete += `<div class="listadoBilletes__item ">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                          <cite class="fs-5 text-primary">Vuelo Num.: ${
                            dataVuelo.vuelo_Id
                          }</cite>
                          <div class="d-flex justify-content-center align-items-center">
                            <i class="bi bi-x fw-bold fs-3 text-primary"></i>
                            <button class="btn listadoBilletes__botonEliminar fs-5 text-primary fw-bold p-0" data-bs-toggle="modal" data-bs-target="#modalEliminarBillete_${
                              dataBilletes[i].billete_Id
                            }" data-idBillete="${
                      dataBilletes[i].billete_Id
                    }">Cancelar billete</button>
                          </div>
                        </div>
                      <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                          <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex flex-column justify-content-center">
                              <p class="fs-3 fw-bold">${horaSalida} <span class="listadoBilletes__guiones">-----------</span></p>
                              <p class="fs-6">${formatearFecha(fechaSalida)}</p>
                              <p class="fs-5">${
                                ciudades[dataVuelo.vuelo_AeropuertoSalida]
                              } (${dataVuelo.vuelo_AeropuertoSalida})</p>
                            </div>
                          <div class="d-flex flex-column mb-4 align-items-center justify-content-center mx-5 mx-sm-0">
                            <img class="listadoBilletes__imagen img-fluid mx-3 w-50 mb-2" src="../../../build/assets/media/icons8-avion-32.png">
                            <p class="listadoBilletes__intervalo fs-5 fw-bold mb-5">${
                              intervalo[0]
                            }h ${intervalo[1]}min.</p>
                          </div>
                        <div class="d-flex flex-column align-items-end">
                            <p class="fs-3 fw-bold"><span class="listadoBilletes__guiones">-----------</span> ${horaLlegada}</p>
                            <p class="fs-6">${formatearFecha(fechaLlegada)}</p>
                            <p class="fs-5">${
                              ciudades[dataVuelo.vuelo_AeropuertoLlegada]
                            } (${dataVuelo.vuelo_AeropuertoLlegada})</p>
                          </div>
                      </div>
                      <div class="d-flex flex-column justify-content-center align-items-center">
                          <p class="fw-bold text-center">Precio:</p>
                          <p>${dataBilletes[i].billete_Precio}€</p>
                      </div>
                      <div>
                        <p class="fw-bold">Asiento: </p>
                        <div class="d-flex justify-content-center align-items-center">`;
                    if (dataBilletes[i].billete_Asiento.substr(1) <= 6) {
                      billete += `<img class="listadoBilletes__asiento img-fluid" src="../../../build/assets/media/asiento.png">`;
                    } else if (
                      dataBilletes[i].billete_Asiento.substr(1) >= 7 &&
                      dataBilletes[i].billete_Asiento.substr(1) <= 10
                    ) {
                      billete += `<img class="listadoBilletes__asiento img-fluid" src="../../../build/assets/media/asiento2.png">`;
                    } else {
                      billete += `<img class="listadoBilletes__asiento img-fluid" src="../../../build/assets/media/asiento3.png">`;
                    }

                    billete += `<p>${dataBilletes[i].billete_Asiento}</p>
                      </div>
                    </div>
                  </div>
              </div>`;
                    // Añadimos el billete al listado
                    $(".listadoBilletes__lista").append(billete);

                    $("#modalEliminarBillete").append(
                      `<div class="modal fade" id="modalEliminarBillete_${
                        dataBilletes[i].billete_Id
                      }" tabindex="-1">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                          <h3>¡Aviso! El billete no es reembolsable</h3>
                            <button
                              type="button"
                              class="btn-close"
                              data-bs-dismiss="modal"
                              aria-label="Close"
                            ></button>
                          </div>
                          <div class="modal-body">
                            <p>¿Estás seguro de que desea cancelar el billete del día ${formatearFecha(
                              fechaSalida
                            )} de ${
                        ciudades[dataVuelo.vuelo_AeropuertoSalida]
                      } a ${ciudades[dataVuelo.vuelo_AeropuertoLlegada]}</p>
                          </div>
                          <div class="modal-footer">
                            <button
                              type="button"
                              class="btn text-white fs-4"
                              style="background-color: red"
                              data-bs-dismiss="modal"
                            >
                              No
                            </button>
                            <button type="button" value="${
                              dataBilletes[i].billete_Id
                            }" class="cancelar_billete btn btn-success fs-4">Sí</button>
                          </div>
                        </div>
                      </div>
                    </div>`
                    );
                  });

                  $(document).on("click", ".cancelar_billete", function () {
                    let billete_Id = $(this).val();
                    fetch(
                      "http://localhost/TFG/proyectoTFG/server/public/api/eliminarBillete",
                      {
                        method: "POST",
                        headers: {
                          "Content-Type": "application/json",
                        },
                        body: JSON.stringify({ billete_Id: billete_Id }),
                      }
                    ).then((response) => {
                      if (response.ok) {
                        window.location.href =
                          "http://localhost/TFG/proyectoTFG/client/archivos/listadoBilletes.html?eliminado=true";
                      } else {
                        window.location.href =
                          "http://localhost/TFG/proyectoTFG/client/archivos/error.html";
                      }
                    });
                  });
                } else {
                  window.location.href =
                    "http://localhost/TFG/proyectoTFG/client/archivos/error.html";
                }
              });
            }
          }
        });
      }
    });
  });
});
