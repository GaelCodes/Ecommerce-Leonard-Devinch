<?php
use Stripe\StripeClient;

class PaymentManager
{
  private StripeClient $stripe;
  private $payment_intent;
  private $client_secret;
  public function __construct()
  {
    $this->stripe = new StripeClient($_ENV["STRIPE_TEST_KEY"]);
  }

  public function create_customer(Client $client)
  {
    $customer = $this->stripe->customers->create([
      "email" => $client->get_email(),
      "description" => "My First Test Customer",
      "name" => $client->get_full_name(),
    ]);

    return $customer;
  }

  public function create_payment_intent(Order $order)
  {
    $this->$payment_intent = $this->stripe->paymentIntents->create([
      "customer" => $client_id,
      "currency" => "eur",
      "amount" => 10,
    ]);

    $this->client_secret = $this->$payment_intent["client_secret"];
  }

  public function get_client_secret(): string
  {
    return $this->client_secret;
  }
}
