<?php
include_once "databaseManagerClass.php";
include_once "artistClass.php";

class ArtistsDatabaseManager extends DatabaseManager
{
  function __construct()
  {
    parent::__construct();
  }

  function selectArtistByEmail($artist_email)
  {
    $sqlQuery =
      "SELECT * FROM ARTISTS WHERE artist_email = '" . $artist_email . "'";
    $consultResult = $this->mysqli->query($sqlQuery);
    $consultResult->data_seek(0);
    $row = $consultResult->fetch_assoc();
    $artist = new Artist(
      $row["artist_email"],
      null,
      $row["full_name"],
      $row["total_created_artworks"],
      $row["date_of_birth"],
      $row["style"]
    );

    return $artist;
  }
}
