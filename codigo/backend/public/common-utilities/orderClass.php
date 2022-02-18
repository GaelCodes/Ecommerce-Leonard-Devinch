<?php

class Order
{
  private $client_email;
  private $purchasedArtworks;
  private $total_artworks_adquired;
  private $total_charge;
  private $order_date;
  private $order_id;
  private $status;

  public function __construct(
    Client $client,
    ?array $purchasedArtworks,
    int $order_id = null,
    string $status = null,
    string $order_date = null,
    int $total_artworks_adquired = null,
    float $total_charge = null
  ) {
    if ($purchasedArtworks) {
      // Constructor with purchasedArtworks data
      $this->client_email = $client->get_client_email();
      $this->purchasedArtworks = $purchasedArtworks;
      $this->order_date = $order_date ? $order_date : date("Y-m-d");

      $this->total_artworks_adquired = count($purchasedArtworks);
      $this->calculate_total_charge($purchasedArtworks);

      $this->order_id = $order_id;
      $this->status = $status;
    } else {
      // Constructor without purchasedArtworks data
      $this->client_email = $client->get_client_email();
      $this->purchasedArtworks = $purchasedArtworks;
      $this->order_date = $order_date ? $order_date : date("Y-m-d");

      $this->total_artworks_adquired = $total_artworks_adquired;
      $this->total_charge = $total_charge;

      $this->order_id = $order_id;
      $this->status = $status;
    }
  }

  private function calculate_total_charge(array $purchasedArtworks)
  {
    $this->total_charge = 0;
    for ($i = 0; $i < count($purchasedArtworks); $i++) {
      $charge =
        $purchasedArtworks[$i]->get_units() *
        $purchasedArtworks[$i]->get_price_by_unit();

      $this->total_charge += $charge;
    }
  }

  public function get_order_id(): int
  {
    return $this->order_id;
  }

  public function set_order_id(int $order_id)
  {
    $this->order_id = $order_id;
  }

  public function get_status(): string
  {
    return $this->status;
  }

  public function set_status(string $status)
  {
    $this->status = $status;
  }

  public function get_client_email(): string
  {
    return $this->client_email;
  }

  public function get_total_charge(): float
  {
    return $this->total_charge;
  }

  public function get_total_charge_in_cents(): int
  {
    return intval($this->total_charge * 100);
  }

  public function get_order_date(): string
  {
    return $this->order_date;
  }

  public function get_total_artworks_adquired(): int
  {
    return $this->total_artworks_adquired;
  }

  public function to_array(): array
  {
    $purchased_artworks_as_array = [];

    for ($i = 0; $i < count($this->purchasedArtworks); $i++) {
      $purchased_artworks_as_array[$i] = $this->purchasedArtworks[
        $i
      ]->to_array();
    }

    return [
      "order_id" => $this->order_id,
      "purchased_artworks" => $purchased_artworks_as_array,
      "client_email" => $this->client_email,
      "total_charge" => $this->total_charge,
      "order_date" => $this->order_date,
      "total_artworks_adquired" => $this->total_artworks_adquired,
      "status" => $this->status,
    ];
  }
}
