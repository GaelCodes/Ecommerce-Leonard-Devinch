<?php
use Stripe\StripeClient;

class PaymentManager
{
  private StripeClient $stripe;
  private $payment_intent;
  private $client_secret;
  public function __construct()
  {
    //$this->payment_intent = null;
    $this->stripe = new StripeClient($_ENV["STRIPE_TEST_KEY"]);
  }

  public function create_customer(Client $client)
  {
    $customer = $this->stripe->customers->create([
      "email" => $client->get_client_email(),
      "description" => "My First Test Customer",
      "name" => $client->get_full_name(),
    ]);

    return $customer;
  }

  public function create_payment_intent(Client $client, Order $order)
  {
    $this->payment_intent = $this->stripe->paymentIntents->create([
      "customer" => $client->get_stripe_customer_id(),
      "currency" => "eur",
      "amount" => $order->get_total_charge_in_cents(),
      "payment_method_types" => ["card"],
      "description" =>
        "Intento de pago para la orden con id: " . $order->get_order_id(),
      "metadata" => ["order_id" => $order->get_order_id()],
    ]);

    $this->client_secret = $this->payment_intent["client_secret"];
  }

  public function get_client_secret(): string
  {
    return $this->client_secret;
  }
}
