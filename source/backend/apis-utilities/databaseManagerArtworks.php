<?php
include_once('databaseManager.php');

class databaseManagerArtworks extends databaseManager {


    function __construct(){
        parent::__construct();
    }
    function selectAllArtworks() {
        $consultResult = $this->mysqli->query('SELECT * FROM obras');

        
        for($num_row = 0; $num_row < $consultResult->num_rows ; $num_row++) {
            $consultResult->data_seek($num_row);
            $row = $consultResult->fetch_assoc();
            
            $artworksArray[$num_row] = [
                'title' => $row['TITULO'],
                'url' => $row['URL'],
                'fullNameArtist' => 'Fake FullName',
                'artistEmail' => $row['CORREO_AUTOR'],
                'startDate' => $row['FECHA_INICIO'],
                'endDate' => $row['FECHA_FIN'],
                'availableStock' => $row['CANTIDAD_DISPONIBLE'],
                'createdQuantity' => $row['CANTIDAD_CREADA'],
                'dimensionX' => $row['DIMENSION_X'],
                'dimensionY' => $row['DIMENSION_Y'],
                'price' => $row['PRECIO']
            ];
        }
        return $artworksArray;
    }



}