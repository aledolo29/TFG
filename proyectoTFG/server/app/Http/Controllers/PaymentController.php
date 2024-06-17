<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use PHPMailer\PHPMailer\PHPMailer;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
  public function checkout(Request $request)
  {

    // Recoge los datos de la sesión
    $idCliente = $request->idCliente;
    $origen = $request->origen;
    $destino = $request->destino;
    $asientos = $request->asientos;
    $cliente = $request->cliente;
    $fecha = $request->fecha;
    $hora = $request->hora;
    $precio = $request->precio;

    // Guarda los datos de la sesión
    $request->session()->put('idCliente', $idCliente);
    $request->session()->put('origen', $origen);
    $request->session()->put('destino', $destino);
    $request->session()->put('asientos', $asientos);
    $request->session()->put('cliente', $cliente);
    $request->session()->put('fecha', $fecha);
    $request->session()->put('hora', $hora);
    $request->session()->put('precio', $precio);

    Stripe::setApiKey('sk_test_51P8LM0LUaTwSkShR7VBl9KUmkUnhiT11Qk6CAv234teNUct5ItcLpc6IDtxPCMJ63L3T50LHVF8Vgk2Gpm7Np7KZ00G87Smul1');
    $checkout_session = Session::create([
      'payment_method_types' => ['card'],
      'line_items' => [[
        'price_data' => [
          'currency' => 'eur',
          'product_data' => [
            'name' => 'Vuelo con Interstellar Airlines',
            'description' => 'Vuelo de ' . $origen . ' a ' . $destino . ' el ' . $fecha . ' a las ' . $hora . '. Asientos: ' . $asientos . '. Cliente: ' . $cliente . '.',
          ],
          'unit_amount' => round($precio * 100),
        ],
        'quantity' => 1,
      ]],
      'mode' => 'payment',
      'success_url' => 'https://ruizgijon.ddns.net/domingueza/TFG/proyectoTFG/server/public/success',
      'cancel_url' => 'https://example.com/cancel',
    ]);

    return redirect($checkout_session->url);
  }

  public function sendEmail(Request $request)
  {
    $cuerpoEmail = '';
    $key = 'AIzaSyDmZZChhpXkLXrkFRrgXxGo8g9siH5JSKo';
    $posParentesis = strpos($request->session()->get('destino'), '(');
    $ciudad = substr($request->session()->get('destino'), 0, $posParentesis);
    $responseGeocode = Http::get("https://maps.googleapis.com/maps/api/geocode/json?address=" . $ciudad . "&key=" . $key . "");
    $dataGeocode = $responseGeocode->json();
    $location = $dataGeocode['results'][0]['geometry']['location'];
    $lat = $location['lat'];
    $lng = $location['lng'];

    $responsePlaces = Http::get("https://maps.googleapis.com/maps/api/place/nearbysearch/json?keyword=hotel&location=" . $lat . ", " . $lng . "&radius=5000.0&key=" . $key . "");
    $dataPlaces = $responsePlaces->json();

    $mail = new PHPMailer(true);


    // Envía un email de confirmación al cliente
    $idCliente = $request->session()->get('idCliente');
    $origen = $request->session()->get('origen');
    $destino = $request->session()->get('destino');
    $asientos = $request->session()->get('asientos');
    $nombre = $request->session()->get('cliente');
    $fecha = $request->session()->get('fecha');
    $hora = $request->session()->get('hora');
    $precio = $request->session()->get('precio');

    // Inicio
    // Configuracion SMTP
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;  // Mostrar salida (Desactivar en producción)
    $mail->isSMTP();   // Activar envio SMTP
    $mail->Host  = 'smtp.gmail.com';                     // Servidor SMTP
    $mail->SMTPAuth  = true;  // Identificacion SMTP
    $mail->Username  = 'proyectotfgdaw@gmail.com';                  // Usuario SMTP
    $mail->Password  = 'lozcugypnpjurnrd';   // Contraseña SMTP
    $mail->SMTPSecure = 'ssl';
    $mail->Port  = 465;
    $mail->setFrom('interstellarairlines@gmail.com', 'Interstellar Airlines');  // Remitente del correo


    // Destinatario
    $cliente = Cliente::where('cliente_Id', $idCliente)->first();

    $mail->addAddress($cliente->cliente_Correo, $cliente->cliente_Correo);  // Email y nombre del destinatario
    $mail->isHTML(true);
    $mail->Subject = 'Reserva de vuelo con Interstellar Airlines';
    $cuerpoEmail .= '
        <!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Confirmación de Compra de Billetes</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
      crossorigin="anonymous"
    />
    <style>
      .container {
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
        background-color: #ffffff;
        border: 1px solid #dddddd;
        border-radius: 5px;
        overflow: hidden;
      }
      .header {
        background-color: #325162;
        color: #ffffff;
        padding: 20px;
        text-align: center;
      }
      .content {
        padding: 20px;
      }
      .content h2 {
        color: #333333;
      }
      .ticket {
        border: 1px solid #dddddd;
        border-radius: 5px;
        padding: 10px;
        margin-top: 20px;
        background-color: #f9f9f9;
      }
      .footer {
        background-color: #325162;
        color: #ffffff;
        text-align: center;
        padding: 10px;
        font-size: 12px;
      }
      .footer a {
        color: #ffffff;
        text-decoration: none;
      }
      h3{
        font-weight: bold;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <div class="header">
        <h1>Compra Confirmada</h1>
      </div>
      <div class="content">
        <h2>' . \htmlentities("¡Gracias por tu compra, " . $nombre . "!") . '</h2>
        <p>
          Adjunto ' . htmlentities("encontrarás") . ' tus billetes ' . htmlentities("electrónicos") . '. Por favor, revisa los
          detalles a ' . htmlentities("continuación") . ':
        </p>
        <div class="ticket">
          <h3>Billete ' . htmlentities("Electrónico") . '</h3>
          <p><strong>Nombre: </strong> ' . $nombre . '</p>
          <p><strong>Fecha de Viaje: </strong> ' . $fecha . '</p>
          <p><strong>Hora de Salida: </strong>' . $hora . '</p>
          <p><strong>Origen: </strong>' . $origen . '</p>
          <p><strong>Destino: </strong> ' . $destino . '</p>
          <p><strong>Asiento: </strong> ' . $asientos . '</p>
          <p><strong>Precio: </strong> ' . $precio . '' . htmlentities("€") . '</p>
        </div>
        <p>
          Te recomendamos llegar al menos 30 minutos antes de la hora de salida.
          Si tienes alguna pregunta, no dudes en contactarnos.
        </p>
      </div>
      <div class="footer">
        <p>
          &copy; 2024 ' . htmlentities("Compañia") . ' de Transportes. Todos los derechos reservados.
        </p>
        <p><a href="mailto: @compania.com">interstellarairlines@compania.com</a></p>
      </div>
    </div>
    <div>';
    if ($dataPlaces['status'] == 'OK') {
      $cuerpoEmail .= '<h3>Te dejamos algunas recomendaciones de posibles lugares donde hospedarse en ' . $ciudad . '</h3  >
      <ul>';
      $num = 0;
      if (count($dataPlaces['results']) < 5) {
        $num = count($dataPlaces['results']);
      } else {
        $num = 5;
      }
      for ($i = 0; $i < $num; $i++) {
        $cuerpoEmail .= '<li><strong>' . $dataPlaces['results'][$i]['name'] . '</strong><br>Echar un vistazo: . ' . $dataPlaces['results'][$i]['photos'][0]['html_attributions'][0] . '</li>';
      }
      $cuerpoEmail .= '</ul>';
    }

    $cuerpoEmail .= ' </div>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
      crossorigin="anonymous"
    ></script>
  </body>
</html>';
    $mail->Body = $cuerpoEmail;
    $mail->clearAttachments();
    $mail->addAttachment(storage_path('app/public/qr.webp'));
    $mail->send();

    return view('paymentSuccess');
  }
}
