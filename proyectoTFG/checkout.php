<?php
require 'vendor/autoload.php';

\Stripe\Stripe::setApiKey('sk_test_51P8LM0LUaTwSkShR7VBl9KUmkUnhiT11Qk6CAv234teNUct5ItcLpc6IDtxPCMJ63L3T50LHVF8Vgk2Gpm7Np7KZ00G87Smul1');
$checkout_session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'line_items' => [[
        'price_data' => [
            'currency' => 'eur',
            'product_data' => [
                'name' => 'T-shirt',
                'description' => 'Comfortable cotton t-shirt'
            ],
            'unit_amount' => 2500,
        ],
        'quantity' => 1,
    ]],
    'mode' => 'payment',
    'success_url' => 'https://example.com/success',
    'cancel_url' => 'https://example.com/cancel',
]);
http_response_code(200);
header("Location: " . $checkout_session->url);
