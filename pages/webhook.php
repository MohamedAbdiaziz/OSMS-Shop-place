<?php
session_start();
require '../vendor/autoload.php';
require_once '../classes/transaction.class.php';
require_once '../classes/cart.class.php';




\Stripe\Stripe::setApiKey('sk_test_51PMXeh08OHR1fd54KU9RT8xau5XfcvVcn4yqoc4aPjZBp0x7v9HxtzuG3556RjQW6NvjG0H8rcgfkQtF1mQ9UEpt002EeA0Cwl');

error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: http://localhost:8082");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

$endpoint_secret = 'whsec_fb84105242a41b3acf214bce74b32ba83864dcaebd000b47110d8c73fc0ed50a';

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];

try {
    $event = \Stripe\Webhook::constructEvent(
        $payload, $sig_header, $endpoint_secret
    );
} catch(\UnexpectedValueException $e) {
    // Invalid payload
    http_response_code(400);
    exit();
} catch(\Stripe\Exception\SignatureVerificationException $e) {
    // Invalid signature
    http_response_code(400);
    exit();
}

// Handle the event
switch ($event->type) {
    case 'checkout.session.completed':
        $session = $event->data->object;

        // Fulfill the purchase
        // You can retrieve the session with: \Stripe\Checkout\Session::retrieve($session->id);

        // Database connection
        // $host = 'localhost';
        // $db = 'your_database_name';
        // $user = 'your_database_user';
        // $pass = 'your_database_password';
        // $charset = 'utf8mb4';
        // $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        // $options = [
        //     PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        //     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        //     PDO::ATTR_EMULATE_PREPARES   => false,
        // ];

        // try {
        //     $pdo = new PDO($dsn, $user, $pass, $options);
        // } catch (\PDOException $e) {
        //     echo json_encode(['error' => $e->getMessage()]);
        //     exit;
        // }

        // Update the transaction status
        // $stmt = $pdo->prepare("UPDATE transactions SET status = 'completed' WHERE stripe_session_id = :stripe_session_id");
        // $stmt->execute([':stripe_session_id' => $session->id]);
        $objTrans = new Transaction();
        $objCart = new Cart();
        $objTrans->setStripeSessionId($session->id);
        $objCart->setCid("Yussuf488");
        $objTrans->UpdateTransaction();
        $objTrans->TransSession(1);
        $objCart->revomeAll();



        break;
    case 'checkout.session.expired':
        $objTrans = new Transaction();
        
        $objTrans->setStripeSessionId($session->id);
        
        // $objTrans->UpdateTransaction();
        $objTrans->TransSession();
        
    default:
        echo 'Received unknown event type ' . $event->type;
}

http_response_code(200);
