<?php

require_once __ROOT__ .
  "/public/stripe-webhooks/webhooks-utilities/DBMForWebhooks.php";
require_once __ROOT__ .
  "/public/stripe-webhooks/webhooks-utilities/pdfsManager.php";
require_once __ROOT__ .
  "/public/stripe-webhooks/webhooks-utilities/firestorageManager.php";

require_once __ROOT__ .
  "/public/stripe-webhooks/webhooks-utilities/notificationsManager.php";

require_once __ROOT__ . "/public/apis-utilities/v1/orderClass.php";

class PaymentSucceedManager
{
  private \Stripe\PaymentIntent $paymentIntent;
  private DBMForWebhooks $dbm;
  private PdfsManager $pdfsManager;
  private FirestorageManager $firestorageManager;
  private NotificationsManager $notifier;

  public function __construct(\Stripe\PaymentIntent $paymentIntent)
  {
    $this->paymentIntent = $paymentIntent;
    $this->dbm = new DBMForWebhooks();
    $this->pdfsManager = new PdfsManager();
    $this->firestorageManager = new FirestorageManager();
    $this->notifier = new NotificationsManager();
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

    // Create PDF Day 10/02/2022
    $this->pdfsManager->create_pdf(
      $client,
      $order,
      $purchasedArtworks,
      $this->paymentIntent
    );

    // Upload PDF 10/02/2022
    //$pdf = $this->pdfsManager->get_pdf();
    //$this->firestorageManager->uploadPDF($client, $pdf);

    // TODO: Send email with PDF 10/02/2022
    //$this->notifier->send_order_succeed_email($pdf);
  }
}
