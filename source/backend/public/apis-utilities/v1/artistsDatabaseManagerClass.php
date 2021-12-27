<?php
include_once('databaseManagerClass.php');
include_once('artistClass.php');

class ArtistsDatabaseManager extends DatabaseManager {
    function __construct(){
        parent::__construct();
    }

    function selectArtistByEmail($artistEmail) {
        $sqlQuery = "SELECT * FROM PINTORES WHERE CORREO = '".$artistEmail."'";
        $consultResult = $this->mysqli->query($sqlQuery);
        $consultResult->data_seek(0);
        $row = $consultResult->fetch_assoc();
        $artist = new Artist(
            $row['CORREO'],
            null,
            $row['NOMBRE_COMPLETO'],
            $row['CANTIDAD_DE_OBRAS'],
            $row['FECHA_DE_NACIMIENTO'],
            $row['ESTILO']
        );       
        return $artist;
    }
}