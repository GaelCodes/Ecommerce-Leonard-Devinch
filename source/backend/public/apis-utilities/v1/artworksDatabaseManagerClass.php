<?php
include_once('databaseManagerClass.php');
include_once('artistsDatabaseManagerClass.php');
include_once('artworkClass.php');

class ArtworksDatabaseManager extends DatabaseManager {

    private $artistsDatabaseManager;
    function __construct(){
        parent::__construct();
        $this->artistsDatabaseManager = new ArtistsDatabaseManager();
    }

    function selectAllArtworks() {
        $consultResult = $this->mysqli->query('SELECT * FROM obras');

        
        for($num_row = 0; $num_row < $consultResult->num_rows ; $num_row++) {
            $consultResult->data_seek($num_row);
            $row = $consultResult->fetch_assoc();
            
            $artworkArtist = $this->artistsDatabaseManager->selectArtistByEmail($row['CORREO_AUTOR']);
            $artworkArtist = $artworkArtist->toArray();
            
            $artwork = new Artwork(
                $row['TITULO'],
                $row['URL'],
                $artworkArtist,
                $row['TEMATICA'],
                $row['FECHA_INICIO'],
                $row['FECHA_FIN'],
                $row['CANTIDAD_DISPONIBLE'],
                $row['CANTIDAD_CREADA'],
                $row['DIMENSION_X'],
                $row['DIMENSION_Y'],
                $row['PRECIO']
            );

            $artworksArray[$num_row] = $artwork->toArray();
        }
        
        return $artworksArray;
    }

}