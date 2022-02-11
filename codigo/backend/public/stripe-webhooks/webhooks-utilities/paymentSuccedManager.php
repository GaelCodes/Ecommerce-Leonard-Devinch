<?php

require_once __ROOT__ .
  "/public/common-utilities/firebaseStorageManagerClass.php";
require_once __ROOT__ . "/public/common-utilities/orderClass.php";
require_once __ROOT__ .
  "/public/stripe-webhooks/webhooks-utilities/DBMForWebhooks.php";
require_once __ROOT__ .
  "/public/stripe-webhooks/webhooks-utilities/pdfsManager.php";

use Stripe\PaymentIntent;

class PaymentSucceedManager
{
  private PaymentIntent $paymentIntent;
  private DBMForWebhooks $dbm;
  private PdfsManager $pdfsManager;
  private FirebaseStorageManager $firebaseStorageManager;

  public function __construct(PaymentIntent $paymentIntent)
  {
    $this->paymentIntent = $paymentIntent;
    $this->dbm = new DBMForWebhooks();
    $this->pdfsManager = new PdfsManager();
    $this->firebaseStorageManager = new FirebaseStorageManager();
  }

  public function start()
  {
    // Retrieve request data
    $stripe_customer_id = $this->paymentIntent->customer;
    $order_id = $this->paymentIntent->metadata->order_id;
    $order_id = intval($order_id);

    $client = $this->dbm->get_client_by_stripe_id($stripe_customer_id);
    $purchasedArtworks = $this->dbm->get_purchased_artworks_by_order_id(
      $order_id
    );
    $order = new Order($client, $purchasedArtworks, $order_id);

    // Update database
    $order->set_status("pagada");
    $this->dbm->update_order_status($order);
    $this->dbm->update_available_quantity($purchasedArtworks);

    // Create PDF
    $this->pdfsManager->create_pdf(
      $client,
      $order,
      $purchasedArtworks,
      $this->paymentIntent
    );

    // Upload PDF
    $pdf_path = $this->pdfsManager->get_pdf_path();
    $this->firebaseStorageManager->upload_pdf($client, $order, $pdf_path);

    // No se enviarán las factura de los clientes por correo.
    // No aprenderé nada que no sepa ya, el tema del envío por correo
    // se trató bastante en el proyecto "My Anime"
  }
}
