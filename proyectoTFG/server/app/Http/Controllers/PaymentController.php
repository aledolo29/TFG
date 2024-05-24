<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function checkout(Request $request)
    {

        // Recoge los datos de la sesión
        $origen = $request->origen;
        $destino = $request->destino;
        $asientos = $request->asientos;
        $cliente = $request->cliente;
        $fecha = $request->fecha;
        $hora = $request->hora;
        $precio = $request->precio;


        var_dump($origen, $destino, $asientos, $cliente, $fecha, $hora, $precio);


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
            'success_url' => route('paymentSuccess', ['session_id' => ':session_id']),
            'cancel_url' => 'https://example.com/cancel',
        ]);

        return redirect($checkout_session->url);
    }

    public function handlePaymentCompleted(Request $request)
    {
        // Establece la clave de API de Stripe
        Stripe::setApiKey('sk_test_51P8LM0LUaTwSkShR7VBl9KUmkUnhiT11Qk6CAv234teNUct5ItcLpc6IDtxPCMJ63L3T50LHVF8Vgk2Gpm7Np7KZ00G87Smul1');
    
        // Obtén el ID de la sesión de pago desde la solicitud
        $sessionId = $request->get('session_id');
    
        // Recupera la sesión de pago desde Stripe
        $session = \Stripe\Checkout\Session::retrieve($sessionId);
    
        // Recupera el PaymentIntent desde Stripe
        $paymentIntent = \Stripe\PaymentIntent::retrieve($session->payment_intent);
    
        // Obtén el correo electrónico del cliente
        $customerEmail = $paymentIntent->receipt_email;
    
        header("location: http://localhost/TFG/proyectoTFG/client/" . $customerEmail . "/profile");
    
        // ...
    }
}
