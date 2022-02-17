<?php
require_once "databaseManagerClass.php";
require_once "purchasedArtworkClass.php";
require_once "artistsDatabaseManagerClass.php";

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
    $artist_email = $purchasedArtwork->get_artist()->get_artist_email();
    $order_id = $purchasedArtwork->get_order_id();
    $price_by_unit = $purchasedArtwork->get_price_by_unit();
    $units = $purchasedArtwork->get_units();

    $result = $stmt->execute();
    $stmt->close();

    return $result;
  }

  function select_filtered_purchased_artworks($filters): array
  {
    $query = $this->prepare_filtered_query($filters);

    $rows = $this->mysqli->query($query);

    if ($rows) {
      $this->artistsDBM = new ArtistsDatabaseManager();
      $purchased_artworks_array = [];

      for ($num_row = 0; $num_row < $rows->num_rows; $num_row++) {
        $rows->data_seek($num_row);
        $data = $rows->fetch_assoc();

        /**
         * Uncoment following lines if want to get artist data too
         * change $data["artist_email"] to $purchased_artwork_artist
         * change PurchasedArtwork constructor
         */

        $artist = $this->artistsDBM->selectArtistByEmail($data["artist_email"]);

        $purchased_artwork = new PurchasedArtwork(
          $data["artwork_title"],
          $data["artist_email"],
          floatval($data["price_by_unit"]),
          intval($data["units"]),
          intval($data["order_id"]),
          $artist
        );

        $purchased_artworks_array[$num_row] = $purchased_artwork;
      }

      return $purchased_artworks_array;
    } else {
      throw new Exception("Error selecting filtered purchased artworks", 1);
    }
  }

  private function prepare_filtered_query($filters)
  {
    $query = "SELECT * FROM PURCHASED_ARTWORKS WHERE ";

    if (isset($filters["artwork_title"])) {
      $query .= " artwork_title = '" . $filters["artwork_title"] . "' AND";
    }

    if (isset($filters["artist_email"])) {
      $query .= " artist_email = '" . $filters["artist_email"] . "' AND";
    }

    if (isset($filters["order_id"])) {
      $query .= " order_id = " . $filters["order_id"] . " AND ";
    }

    // Eliminación del último AND
    $query = preg_replace("/AND $/", "", $query);

    return $query;
  }
}
