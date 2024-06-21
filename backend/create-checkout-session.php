<?php
require '../vendor/autoload.php';
require_once '../classes/cart.class.php';
require_once '../classes/transaction.class.php';

\Stripe\Stripe::setApiKey('sk_test_51PMXeh08OHR1fd54KU9RT8xau5XfcvVcn4yqoc4aPjZBp0x7v9HxtzuG3556RjQW6NvjG0H8rcgfkQtF1mQ9UEpt002EeA0Cwl');

error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: http://localhost:8082");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

// Database connection


// Example customer ID
$objCart = new Cart();
$objCart->setCid("Yussuf488");
try {
    // Retrieve cart items from the database
    $cartItems = $objCart->getCartItemsById();
    if (!$cartItems) {
        throw new Exception('No items in cart');
    }

    // Prepare line items for Stripe Checkout
    $lineItems = [];
    foreach ($cartItems as $item) {
        $lineItems[] = [
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => $item['ProductName'],
                ],
                'unit_amount' => $item['ProductPrice'] * 100, // Convert dollars to cents
            ],
            'quantity' => $item['Quantity'],
        ];
    }

    // Create a new Stripe Checkout session
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => $lineItems,
        'mode' => 'payment',
        'success_url' => 'http://localhost:8082/osm/pages/shop.php',
        'cancel_url' => 'http://localhost:8082/osm/pages/cart.php',
    ]);

    // Store the session ID in the database (you may want to store additional data)
    // $stmt = $pdo->prepare("INSERT INTO transactions (customer_id, stripe_session_id, amount) VALUES (:customer_id, :stripe_session_id, :amount)");
    // $stmt->execute([
    //     ':customer_id' => $customerId,
    //     ':stripe_session_id' => $session->id,
    //     ':amount' => array_sum(array_column($cartItems, 'ProductPrice'))
    // ]);

    $objtrans = new Transaction();
    $objtrans->setCid("Yussuf488");
    $objtrans->setAmount(array_sum(array_column($cartItems, 'Subtotal')));
    $objtrans->setStripeSessionId($session->id);

    // echo $objtrans->getCid();
    // echo "</br>";
    // echo $objtrans->getAmount();
    // echo $objtrans->getStripeSessionId();
    $objtrans->AddTransaction($cartItems);

    echo json_encode(['id' => $session->id]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
