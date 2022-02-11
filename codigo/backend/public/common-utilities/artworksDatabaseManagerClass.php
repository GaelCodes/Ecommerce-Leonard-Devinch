<?php

require_once "databaseManagerClass.php";
require_once "artistsDatabaseManagerClass.php";
require_once "artworkClass.php";

class ArtworksDatabaseManager extends DatabaseManager
{
  // El DBM de las obras de arte necesita
  // al DBM de los artistas para recuperar
  // los datos de los artista de las obras de arte.

  private $artistsDBM;

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

  function selectFilteredArtworks($filters): array
  {
    $query = $this->prepareFilteredQuery($filters);

    $consultResult = $this->mysqli->query($query);

    if ($consultResult) {
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
    } else {
      return false;
    }
  }

  private function prepareFilteredQuery($filters)
  {
    $query = "SELECT * FROM ARTWORKS WHERE ";

    if (isset($filters["title"])) {
      $query .= " title LIKE '%" . $filters["title"] . "%' AND";
    }

    if (isset($filters["author"])) {
      $query .=
        " artist_email IN (SELECT artist_email FROM ARTISTS WHERE full_name LIKE '%" .
        $filters["author"] .
        "%' ) AND ";
    }

    if (isset($filters["ids"])) {
      // Objective: "id IN ( 0, 1, 2...) AND "
      $total_ids = count($filters["ids"]);
      $query .= " id IN (";

      for ($i = 0; $i < $total_ids; $i++) {
        $query .= $filters["ids"][$i];

        $last = $total_ids - $i == 1;
        if (!$last) {
          $query .= ",";
        }
      }

      $query .= ") AND ";
    }

    if (isset($filters["topics"])) {
      $query .= " topics LIKE '%" . $filters["topics"] . "%' AND ";
    }

    // Las fechas de inicio y fecha de finalización
    // compondrán un intervalo de tiempo,
    // se seleccionarán las obras creadas dentro de este intervalo de tiempo.

    if (isset($filters["starting_date"])) {
      $query .= " starting_date >= '" . $filters["starting_date"] . "' AND ";
    }

    if (isset($filters["ending_date"])) {
      $query .= " ending_date <= '" . $filters["ending_date"] . "' AND ";
    }

    if (isset($filters["available"])) {
      $query .= " available_quantity < created_quantity AND ";
    }

    if (isset($filters["dimension_x"])) {
      $query .= " dimension_x = " . $filters["dimension_x"] . " AND ";
    }

    if (isset($filters["dimension_y"])) {
      $query .= " dimension_y = " . $filters["dimension_y"] . " AND ";
    }

    if (isset($filters["price"])) {
      $query .= " price >= " . $filters["price"]["minimum"] . " AND ";

      $query .= " price <= " . $filters["price"]["maximum"];
    }

    // Eliminación del último AND
    $query = preg_replace("/AND $/", "", $query);

    return $query;
  }

  function selectArtworkByPK(string $title, string $artist_email): Artwork
  {
    $query =
      "SELECT * FROM ARTWORKS where title = '" .
      $title .
      "' AND artist_email = '" .
      $artist_email .
      "'";
    $row = $this->mysqli->query($query);

    if ($row) {
      $data = $row->fetch_array(MYSQLI_ASSOC);

      $data["artist"] = ["artist_email" => $data["artist_email"]];

      $artwork = new Artwork(
        $data["title"],
        $data["url"],
        $data["artist"],
        $data["topics"],
        $data["starting_date"],
        $data["ending_date"],
        $data["available_quantity"],
        $data["created_quantity"],
        $data["dimension_x"],
        $data["dimension_y"],
        $data["price"]
      );
    } else {
      throw new Exception("Error selecting artwork by PK", 1);
    }

    return $artwork;
  }

  public function updateArtwork(Artwork $artwork)
  {
    $stmt = $this->mysqli->prepare(
      "UPDATE ARTWORKS SET title = ?, url = ?, artist_email = ?, topics = ?, starting_date = ?, ending_date = ?, available_quantity = ?, created_quantity = ?, dimension_x = ?, dimension_y = ?, price = ? WHERE  title = '" .
        $artwork->get_title() .
        "' AND artist_email = '" .
        $artwork->get_artist()["artist_email"] .
        "'"
    );

    $stmt->bind_param(
      "ssssssiiddd",
      $title,
      $url,
      $artist_email,
      $topics,
      $starting_date,
      $ending_date,
      $available_quantity,
      $created_quantity,
      $dimension_x,
      $dimension_y,
      $price
    );

    $title = $artwork->get_title();
    $url = $artwork->get_url();
    $artist_email = $artwork->get_artist()["artist_email"];
    $topics = $artwork->get_topics();
    $starting_date = $artwork->get_starting_date();
    $ending_date = $artwork->get_ending_date();
    $available_quantity = $artwork->get_available_quantity();
    $created_quantity = $artwork->get_created_quantity();
    $dimension_x = $artwork->get_dimension_x();
    $dimension_y = $artwork->get_dimension_y();
    $price = $artwork->get_price();

    $result = $stmt->execute();
    $stmt->close();

    if ($result) {
      return true;
    } else {
      throw new Exception("Error trying to update an artwork", 1);
    }
  }
}
