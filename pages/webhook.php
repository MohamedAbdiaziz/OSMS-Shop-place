<?php

include_once('../db/session.php');
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

// Logging function
function log_message($message) {
    file_put_contents('webhook.log', date('Y-m-d H:i:s') . " - " . $message . PHP_EOL, FILE_APPEND);
}

log_message("Received webhook with payload: " . $payload);
log_message("Session customer ID: " . (isset($_SESSION['customer']) ? $_SESSION['customer'] : "Not set"));

try {
    $event = \Stripe\Webhook::constructEvent(
        $payload, $sig_header, $endpoint_secret
    );
} catch(\UnexpectedValueException $e) {
    log_message("Invalid payload: " . $e->getMessage());
    http_response_code(400);
    exit();
} catch(\Stripe\Exception\SignatureVerificationException $e) {
    log_message("Invalid signature: " . $e->getMessage());
    http_response_code(400);
    exit();
}

log_message("Webhook event type: " . $event->type);

// Handle the event
switch ($event->type) {
    case 'checkout.session.completed':
        $session = $event->data->object;
        log_message("Handling checkout.session.completed for session: " . $session->id);

        try {
            $objTrans = new Transaction();
            $objCart = new Cart();
            $objTrans->setStripeSessionId($session->id);
            $objCart->setCid($_SESSION['customer']);
            $objTrans->UpdateTransaction();
            $objCart->revomeAll();
            log_message("Transaction updated and cart cleared for session: " . $session->id);
        } catch (Exception $e) {
            log_message("Error handling checkout.session.completed: " . $e->getMessage());
        }
        break;

    case 'checkout.session.expired':
        $session = $event->data->object;
        log_message("Handling checkout.session.expired for session: " . $session->id);

        try {
            $objTrans = new Transaction();
            $objTrans->setStripeSessionId($session->id);
            // Assuming TransSession method handles expired sessions
            $objTrans->TransSession();
            log_message("Transaction session handled for expired session: " . $session->id);
        } catch (Exception $e) {
            log_message("Error handling checkout.session.expired: " . $e->getMessage());
        }
        break;

    default:
        log_message("Received unknown event type: " . $event->type);
        echo 'Received unknown event type ' . $event->type;
}

http_response_code(200);
