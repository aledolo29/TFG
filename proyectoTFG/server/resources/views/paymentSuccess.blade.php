<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/successStyles.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <title>Pago Completado</title>
</head>

<body>
    <div class="m-auto w-50 d-flex flex-column justify-content-center align-items-center mt-5">
        <div class="logo">
            <img src="{{ asset('storage/logo_azul.png') }}" class="img-fluid" alt="logo">
        </div>
        <div class="d-flex justify-content-center align-items-center">
            <h2 class="text-center me-2">Pago completado exitosamente</h2>
            <div class="checked">
                <img src="{{ asset('storage/checked.png') }}" class="img-fluid w-75" alt="checked">
            </div>
        </div>
        <p class="text-center"> ¡Gracias por tu compra! En breve recibirás un correo con los detalles de
            tu pedido.</p>
        <a id="guardarVueloBillete" class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="http://localhost/TFG/proyectoTFG/client/archivos/index.html">Volver al inicio</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script>
        addEventListener("DOMContentLoaded", function() {
            var vueloSeleccionado = decodeURIComponent(sessionStorage.getItem("vueloSeleccionado"));
            vueloSeleccionado = JSON.parse(vueloSeleccionado);
            var asientos = JSON.parse(sessionStorage.getItem("asientosSeleccionados"));
            var idCliente = localStorage.getItem("idCliente");
            console.log(idCliente);
            fetch('http://localhost/TFG/proyectoTFG/server/public/api/guardarVueloBillete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    idCliente: idCliente,
                    vueloSeleccionado: vueloSeleccionado,
                    asientos: asientos
                }),
            }).then(function(response) {
                if (response.ok) {
                    localStorage.removeItem("vuelos");
                    sessionStorage.removeItem("vueloSeleccionado");
                    sessionStorage.removeItem("asientosSeleccionados");
                } else {
                    console.log(response);
                    console.log("Error al guardar el vuelo y billete");
                }
            })
        });
    </script>
</body>

</html>