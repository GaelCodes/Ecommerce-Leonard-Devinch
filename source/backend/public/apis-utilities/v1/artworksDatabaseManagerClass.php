<?php
include_once "databaseManagerClass.php";
include_once "artistsDatabaseManagerClass.php";
include_once "artworkClass.php";

class ArtworksDatabaseManager extends DatabaseManager
{
  private $artistsDatabaseManager;
  function __construct()
  {
    parent::__construct();
    $this->artistsDatabaseManager = new ArtistsDatabaseManager();
  }

  function selectAllArtworks()
  {
    $consultResult = $this->mysqli->query("SELECT * FROM ARTWORKS");

    for ($num_row = 0; $num_row < $consultResult->num_rows; $num_row++) {
      $consultResult->data_seek($num_row);
      $row = $consultResult->fetch_assoc();

      $artworkArtist = $this->artistsDatabaseManager->selectArtistByEmail(
        $row["artist_email"]
      );
      $artworkArtist = $artworkArtist->toArray();

      $artwork = new Artwork(
        $row["title"],
        $row["url"],
        $artworkArtist,
        $row["topics"],
        $row["starting_date"],
        $row["ending_date"],
        $row["available_quantity"],
        $row["created_quantity"],
        $row["dimension_x"],
        $row["dimension_y"],
        $row["price"]
      );

      $artworksArray[$num_row] = $artwork->toArray();
    }

    return $artworksArray;
  }
}
