<?php
require_once __ROOT__ . "/public/apis-utilities/v1/databaseManagerClass.php";

class PurchasedArtworksDatabaseManager extends DatabaseManager
{
  public function __construct()
  {
    parent::__construct();
  }

  public function insert_purchased_artwork(PurchasedArtwork $purchasedArtwork)
  {
    $stmt = $this->mysqli->prepare(
      "INSERT INTO PURCHASED_ARTWORKS(artwork_title,artist_email,order_id,price_by_unit,units) VALUES(?,?,?,?,?)"
    );

    $stmt->bind_param(
      "ssiii",
      $artwork_title,
      $artist_email,
      $order_id,
      $price_by_unit,
      $units
    );

    $artwork_title = $purchasedArtwork->get_artwork_title();
    $artist_email = $purchasedArtwork->get_artist_email();
    $order_id = $purchasedArtwork->get_order_id();
    $price_by_unit = $purchasedArtwork->get_price_by_unit();
    $units = $purchasedArtwork->get_units();

    $result = $stmt->execute();
    $stmt->close();

    return $result;
  }
}
