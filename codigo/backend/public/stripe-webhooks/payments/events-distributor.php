<?php
// Distribuidor de eventos Payments de Stripe events-distributor.php
//
//
require_once "load_dependencies.php";

// Defino el directorio backend como la constante __ROOT__
define("__ROOT__", dirname(dirname(dirname(dirname(__FILE__)))));

require_once __ROOT__ .
  "/public/stripe-webhooks/webhooks-utilities/paymentSuccedManager.php";

//require_once __ROOT__ . "/webhooks-utilities/paymentFailedManager.php";
//require_once __ROOT__ . "/webhooks-utilities/paymentCanceledManager.php";

$endpoint_secret = $_ENV["WH_SECRET"];
$payload = @file_get_contents("php://input");
$sig_header = $_SERVER["HTTP_STRIPE_SIGNATURE"];
$event = null;

try {
  $event = \Stripe\Webhook::constructEvent(
    $payload,
    $sig_header,
    $endpoint_secret
  );
} catch (\UnexpectedValueException $e) {
  // Invalid payload
  http_response_code(400);
  exit();
} catch (\Stripe\Exception\SignatureVerificationException $e) {
  // Invalid signature
  http_response_code(400);
  exit();
}

// Handle the event
switch ($event->type) {
  case "payment_intent.amount_capturable_updated":
    $paymentIntent = $event->data->object;

    echo "Hola me has enviado un capturable_updated ?";

    break;
  case "payment_intent.canceled":
    $paymentIntent = $event->data->object;

    //$paymentCM = new PaymentCanceledManager($paymentIntent);
    //$paymentCM->start();
    echo "Hola me has enviado un canceled ?";

    break;
  case "payment_intent.created":
    $paymentIntent = $event->data->object;

    break;
  case "payment_intent.payment_failed":
    $paymentIntent = $event->data->object;
    //$paymentFM = new PaymentFailedManager($paymentIntent);
    //$paymentFM->start();

    break;
  case "payment_intent.processing":
    $paymentIntent = $event->data->object;

    break;
  case "payment_intent.requires_action":
    $paymentIntent = $event->data->object;

    break;
  case "payment_intent.succeeded":
    $paymentIntent = $event->data->object;
    $paymentSM = new PaymentSucceedManager($paymentIntent);
    $paymentSM->start();

    break;
  default:
    error_log("Received unknown event type " . $event->type);
}

http_response_code(200);
