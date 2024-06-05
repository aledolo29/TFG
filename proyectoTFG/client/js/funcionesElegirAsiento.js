// ---------------------------------------
// Función para pintar asientos
function pintarAsientos() {
  var asientosOcupados = [];
  const totalAsientos = 90;
  const asientosPorFila = 6;
  var contador = 0;
  var numTitle = 1;
  const letras = ["A", "B", "C", "D", "E", "F"];
  var section = $(".asientos");
  var vuelo = sessionStorage.getItem("vueloSeleccionado");
  vuelo = JSON.parse(decodeURIComponent(vuelo));

  fetch(
    "http://localhost/TFG/proyectoTFG/server/public/api/comprobarAsientos",
    {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ vuelo_Id: vuelo.vuelo_Id }),
    }
  ).then((response) => {
    if (response.status == 200) {
      response.json().then((dataAsientos) => {
        if (dataAsientos !== false) {
          dataAsientos.forEach((asiento) => {
            asientosOcupados.push(asiento);
          });
        }
        var tabla = `
            <cite><span class="text-danger">*</span> Seleccione ${vuelo.vuelo_Num_Pasajeros} asiento/s</cite>
            <table class="table w-50">
              <thead>
                <tr>
                  <th class="text-center bg-transparent border-0 fs-3" scope="col">A</th>
                  <th class="text-center bg-transparent border-0 fs-3" scope="col">B</th>
                  <th class="text-center bg-transparent border-0 fs-3" scope="col">C</th>
                  <th class="bg-transparent border-0"></th>
                  <th class="text-center bg-transparent border-0 fs-3" scope="col">D</th>
                  <th class="text-center bg-transparent border-0 fs-3" scope="col">E</th>
                  <th class="text-center bg-transparent border-0 fs-3" scope="col">F</th>
                </tr>
              </thead>
            <tbody>`;

        for (let i = 1; i <= totalAsientos; i++) {
          if (i % asientosPorFila == 1) {
            tabla += `<tr>`;
          }
          if (i % 6 == 4) {
            contador++;
            tabla += `<td class="text-center bg-transparent border-0 fs-3">${contador}</td>`;
          }
          // Pintar asientos
          // Asientos desembarque rápido
          if (i <= 36) {
            if (
              asientosOcupados.includes(letras[(i - 1) % 6] + "" + numTitle)
            ) {
              tabla += `<td class="text-center bg-transparent border-0"><button class="ocupado border-0 p-4 my-1" disabled></button></td>`;
            } else {
              tabla += `<td class="text-center bg-transparent border-0" title="${
                letras[(i - 1) % 6]
              }${numTitle}\nPrecio: ${vuelo.precio * 1.5}€"><button id="${
                letras[(i - 1) % 6]
              }${numTitle}" class="btn_asiento btn btn-warning p-4 my-1"></button></td>`;
            }

            // Asientos businness
          } else if (i > 36 && i <= 60) {
            if (
              asientosOcupados.includes(letras[(i - 1) % 6] + "" + numTitle)
            ) {
              tabla += `<td class="text-center bg-transparent border-0"><button class="ocupado border-0 p-4 my-1" disabled></button></td>`;
            } else {
              tabla += `<td class="text-center bg-transparent border-0" title="${
                letras[(i - 1) % 6]
              }${numTitle}\nPrecio: ${vuelo.precio * 3}€"><button id="${
                letras[(i - 1) % 6]
              }${numTitle}" style="background-color:#c343ff" class="btn_asiento btn p-4 my-1"></button></td>`;
            }

            // Asientos turista
          } else {
            if (
              asientosOcupados.includes(letras[(i - 1) % 6] + "" + numTitle)
            ) {
              tabla += `<td class="text-center bg-transparent border-0"><button class="ocupado border-0 p-4 my-1" disabled></button></td>`;
            } else {
              tabla += `<td class="text-center bg-transparent border-0" title="${
                letras[(i - 1) % 6]
              }${numTitle}\nPrecio: ${vuelo.precio}€"><button id="${
                letras[(i - 1) % 6]
              }${numTitle}" class="btn_asiento btn btn-primary p-4 my-1"></button></td>`;
            }
          }

          if (i % asientosPorFila == 0) {
            numTitle++;
            tabla += `</tr>`;
          }
        }
        tabla += `</tbody>
                    </table>
                    <div class="d-flex justify-content-center"><input type="button" class="reservar_asiento__btn border-0 fw-bold py-3 px-5" id="btn_reservar" value="Reservar"></div>
                    `;
        section.append(tabla);

        var btnAsientos = $(".reservar_asiento__btn");
        btnAsientos.click(function (e) {
          e.preventDefault();
          var asientos = $(".btn_asiento");
          var asientosSeleccionados = [];
          asientos.each(function () {
            if (
              $(this).css("background-color") == "rgb(0, 128, 0)" ||
              $(this).attr("class").includes("btn-success")
            ) {
              asientosSeleccionados.push($(this).attr("id"));
            }
          });
          if (asientosSeleccionados.length < vuelo.vuelo_Num_Pasajeros) {
            $("#mensaje_error").text(
              `Debes seleccionar ${vuelo.vuelo_Num_Pasajeros} asiento/s para continuar.`
            );
            $("#mensaje_error").addClass("alert alert-danger text-center");
            return;
          } else if (asientosSeleccionados.length == 0) {
            $("#mensaje_error").text(`No has seleccionado ningún asiento.`);
            $("#mensaje_error").addClass("alert alert-danger text-center");
            return;
          }
          sessionStorage.setItem(
            "asientosSeleccionados",
            JSON.stringify(asientosSeleccionados)
          );
          window.location.href =
            "http://localhost/TFG/proyectoTFG/client/archivos/pagarVuelo.html";
        });
      });
    }
  });
}

// ---------------------------------------
// Rellenar datos de pasajero
$(document).ready(function () {
  var vuelo = sessionStorage.getItem("vueloSeleccionado");
  vuelo = JSON.parse(decodeURIComponent(vuelo));
  if (vuelo == null) {
    window.location.href =
      "http://localhost/TFG/proyectoTFG/client/archivos/error.html";
  }
  seguridad();

  pintarAsientos();

  $(document).on("click", ".btn_asiento", function (e) {
    e.preventDefault();
    var asiento = $(".btn_asiento");

    // Si el botón está deshabilitado, no hagas nada
    if ($(this).prop("disabled")) {
      return;
    }

    // Botones verder siempre se pueden hacer click
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
    }

    // Obtener todos los botones verdes
    var asientosVerdes = asiento.filter(function () {
      return (
        $(this).css("background-color") == "rgb(0, 128, 0)" ||
        $(this).attr("class").includes("btn-success")
      );
    });
    if (asientosVerdes.length < vuelo.vuelo_Num_Pasajeros) {
      if ($(this).css("background-color") == "rgb(195, 67, 255)") {
        $(this).css("background-color", "");
        $(this).css("border-color", "");
        $(this).addClass("btn-success");
        return;
      } else {
        $(this).css("background-color", "green");
        $(this).css("border-color", "green");
        return;
      }
    }
  });
});
