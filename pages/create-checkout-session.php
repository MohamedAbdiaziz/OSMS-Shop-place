<?php
include_once('../db/session.php');

require '../vendor/autoload.php';
require '../classes/cart.class.php';


\Stripe\Stripe::setApiKey('sk_test_51PMXeh08OHR1fd54KU9RT8xau5XfcvVcn4yqoc4aPjZBp0x7v9HxtzuG3556RjQW6NvjG0H8rcgfkQtF1mQ9UEpt002EeA0Cwl');

header('Content-Type: application/json');

$cartobj = new cart();
try {
    // Retrieve cart items from the request (this is just an example, adjust as needed)
    $cartItems = [
        ['name' => 'Sunglasses', 'price' => 20 * 100, 'quantity' => 1],
        ['name' => 'Oval Nose Pads', 'price' => 3 * 100, 'quantity' => 2],
    ];

    $lineItems = [];
    foreach ($cartItems as $item) {
        $lineItems[] = [
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => $item['name'],
                ],
                'unit_amount' => $item['price'],
            ],
            'quantity' => $item['quantity'],
        ];
    }
//     echo "<pre>";
// print_r($lineItems);
// echo "</pre>";
exit();
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => $lineItems,
        'mode' => 'payment',
        'success_url' => 'http://localhost:8082/testingcheckout/success.php?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => 'http://localhost:8082/testingcheckout/cancel.php',
    ]);
    $_SESSION['checkout_session_id'] = $session->id;

    echo json_encode(['id' => $session->id]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
