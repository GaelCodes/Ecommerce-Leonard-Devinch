<?php
include_once "databaseManagerClass.php";
include_once "artistsDatabaseManagerClass.php";
include_once "artworkClass.php";

class ArtworksDatabaseManager extends DatabaseManager
{
  // El DBM de las obras de arte necesita
  // al DBM de los artistas para recuperar
  // los datos de los artista de las obras de arte.

  private $artistsDatabaseManager;

  function __construct()
  {
    parent::__construct();
    $this->artistsDBM = new ArtistsDatabaseManager();
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

  function selectFilteredArtworks($filters)
  {
    $query = $this->prepareFilteredQuery($filters);

    $consultResult = $this->mysqli->query($query);

    for ($num_row = 0; $num_row < $consultResult->num_rows; $num_row++) {
      $consultResult->data_seek($num_row);
      $row = $consultResult->fetch_assoc();

      $artworkArtist = $this->artistsDBM->selectArtistByEmail(
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

  private function prepareFilteredQuery($filters)
  {
    $query = "SELECT * FROM ARTWORKS WHERE ";

    if ($filters["title"]) {
      $query . " title LIKE '%" . $filters["title"] . "%' AND";
    }

    if ($filters["author"]) {
      $query .
        " artist_email IN (SELECT artist_email FROM ARTISTS WHERE full_name LIKE '%" .
        $filters["author"] .
        "%' ) AND ";
    }

    if ($filters["topics"]) {
      $query . " topics LIKE '%" . $filters["topics"] . "%' AND ";
    }

    // Las fechas de inicio y fecha de finalización
    // compondrán un intervalo de tiempo,
    // se seleccionarán las obras creadas dentro de este intervalo de tiempo.

    if ($filters["starting_date"]) {
      $query . " starting_date <= " . $filters["starting_date"] . " AND ";
    }

    if ($filters["ending_date"]) {
      $query . " ending_date >= " . $filters["ending_date"] . " AND ";
    }

    if ($filters["available"]) {
      $query . " available_quantity < created_quantity AND ";
    }

    if ($filters["dimension_x"]) {
      $query . " dimension_x = " . $filters["dimension_x"] . " AND ";
    }

    if ($filters["dimension_y"]) {
      $query . " dimension_y = " . $filters["dimension_y"] . " AND ";
    }

    if ($filters["price"]) {
      $query . " price >= " . $filters["price"]["minimum"] . " AND ";

      $query . " price <= " . $filters["price"]["maximum"];
    }

    // Eliminación del último AND
    $query = preg_replace(" AND $", "", $query);
    return $query;
  }
}
