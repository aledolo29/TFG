// ---------------------------------------
// Función para pintar asientos
function pintarAsientos() {
  var vuelo = sessionStorage.getItem("vueloSeleccionado");
  vuelo = JSON.parse(decodeURIComponent(vuelo));
  if (vuelo == null) {
    window.location.href =
      "http://localhost/TFG/proyectoTFG/client/archivos/error.html";
    return;
  }
  const totalAsientos = 90;
  const asientosPorFila = 6;
  var contador = 0;
  var numTitle = 1;
  const letras = ["A", "B", "C", "D", "E", "F"];
  var section = $(".asientos");
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
      tabla += `<td class="text-center bg-transparent border-0" title="${
        letras[(i - 1) % 6]
      }${numTitle}\nPrecio: ${vuelo.precio * 1.5}€"><button id="${
        letras[(i - 1) % 6]
      }${numTitle}" class="btn_asiento btn btn btn-warning p-4 my-1"></button></td>`;

      // Asientos businness
    } else if (i > 36 && i <= 60) {
      tabla += `<td class="text-center bg-transparent border-0" title="${
        letras[(i - 1) % 6]
      }${numTitle}\nPrecio: ${vuelo.precio * 3}€"><button id="${
        letras[(i - 1) % 6]
      }${numTitle}" style="background-color:#c343ff" class="btn_asiento btn p-4 my-1"></button></td>`;

      // Asientos turista
    } else {
      tabla += `<td class="text-center bg-transparent border-0" title="${
        letras[(i - 1) % 6]
      }${numTitle}\nPrecio: ${vuelo.precio}€"><button id="${
        letras[(i - 1) % 6]
      }${numTitle}" class="btn_asiento btn btn-primary p-4 my-1"></button></td>`;
    }
    if (i % asientosPorFila == 0) {
      numTitle++;
      tabla += `</tr>`;
    }
  }
  tabla += `</tbody>
          </table>
          <div class="d-flex justify-content-center"><input type="button" class="elegir_asiento__btn border-0 fw-bold py-3 px-5" id="btn_reservar" value="Reservar"></div>
          `;
  section.append(tabla);

  var btnAsientos = $(".elegir_asiento__btn");
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
    if (asientosSeleccionados.length == 0) {
      alert("No has seleccionado ningún asiento");
      return;
    }
    sessionStorage.setItem(
      "asientosSeleccionados",
      JSON.stringify(asientosSeleccionados)
    );
    window.location.href =
      "http://localhost/TFG/proyectoTFG/client/archivos/pagarVuelo.html";
  });
}

// ---------------------------------------
// Rellenar datos de pasajero
$(document).ready(function () {
  var vuelo = sessionStorage.getItem("vueloSeleccionado");
  vuelo = JSON.parse(decodeURIComponent(vuelo));
  pintarAsientos();
  var asiento = $(".btn_asiento");

  asiento.click(function (e) {
    e.preventDefault();

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
