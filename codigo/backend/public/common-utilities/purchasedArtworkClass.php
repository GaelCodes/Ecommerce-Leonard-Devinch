<?php

class PurchasedArtwork
{
  private $artwork_title;
  private $artist_email;
  private $price_by_unit;
  private $units;
  private $order_id;
  private $url = null;

  public function __construct(
    string $artwork_title,
    ?string $artist_email,
    float $price_by_unit,
    int $units,
    int $order_id = null,
    Artist $artist = null
  ) {
    $this->artwork_title = $artwork_title;
    $this->artist_email = $artist_email;
    $this->price_by_unit = $price_by_unit;
    $this->units = $units;
    $this->order_id = $order_id;
    $this->artist = $artist;
  }

  public function get_artwork_title(): string
  {
    return $this->artwork_title;
  }

  public function get_artist_email(): string
  {
    return $this->artist_email;
  }

  public function set_artist(Artist $artist): Artist
  {
    $this->artist = $artist;
  }

  public function get_artist(): Artist
  {
    return $this->artist;
  }

  public function get_units(): int
  {
    return $this->units;
  }

  public function get_price_by_unit(): float
  {
    return $this->price_by_unit;
  }

  public function set_order_id(int $order_id)
  {
    $this->order_id = $order_id;
  }

  public function get_order_id(): int
  {
    return $this->order_id;
  }
  public function set_url(string $url)
  {
    $this->url = $url;
  }

  public function get_url(): string
  {
    return $this->url;
  }

  public function to_array(): array
  {
    return [
      "artwork_title" => $this->artwork_title,
      "artist_full_name" => $this->artist->get_full_name(),
      "order_id" => $this->order_id,
      "price_by_unit" => $this->price_by_unit,
      "units" => $this->units,
      "url" => $this->url,
    ];
  }
}
