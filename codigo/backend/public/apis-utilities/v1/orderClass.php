<?php

class Order
{
  private $client_email;
  private $purchasedArtworks;
  private $total_artworks_adquired;
  private $total_charge;
  private $order_date;
  private $order_id;

  public function __construct(string $client_email, array $purchasedArtworks)
  {
    $this->client_email = $client_email;
    $this->purchasedArtworks = $purchasedArtworks;
    $this->order_date = date("Y-m-d");
    $this->total_artworks_adquired = count($purchasedArtworks);

    $this->calculate_total_charge($purchasedArtworks);
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

  public function get_client_email(): string
  {
    return $this->client_email;
  }

  public function get_total_charge(): float
  {
    return $this->total_charge;
  }

  public function get_order_date(): string
  {
    return $this->order_date;
  }

  public function get_total_artworks_adquired(): int
  {
    return $this->total_artworks_adquired;
  }
}
