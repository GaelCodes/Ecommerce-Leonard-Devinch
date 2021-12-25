<?php

class databaseManager {
    private $mysqli;
    private $dbUrl;
    private $dbUser;
    private $dbPassword;
    private $dbSchema;

    function __construct(){
        $this->dbServer = "localhost";
        $this->dbUser = "ecommerce-leonard-devinch";
        $this->dbPassword = "I.B5n9viAD78Z2K(";      
        $this->db = "testing_ecommerce_leonard_devinch";
        $this->port = 3306;
        
        $this->mysqli = new mysqli($this->dbServer,$this->dbUser,$this->dbPassword,$this->db,$this->port);

        // Only for debugging database connection
        // 
        // if ($this->mysqli->connect_errno) {
        //     echo "Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        // }
        // echo $this->mysqli->host_info . "\n";
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