<?php

abstract class DatabaseManager {
    protected $mysqli;
    protected $dbUrl;
    protected $dbUser;
    protected $dbPassword;
    protected $dbSchema;

    function __construct(){
        
        // Production DB Configuration
        // 
        // $this->dbServer = "localhost";
        // $this->dbUser = "ecommerce-leonard-devinch";
        // $this->dbPassword = "I.B5n9viAD78Z2K(";      
        // $this->db = "testing_ecommerce_leonard_devinch";
        // $this->port = 3306;


        // Development DB Configuration
        // 
        // $this->dbServer = "localhost";
        // $this->dbUser = "ecommerce-leonard-devinch";
        // $this->dbPassword = "I.B5n9viAD78Z2K(";      
        // $this->db = "testing_ecommerce_leonard_devinch";
        // $this->port = 3306;
        
        $this->mysqli = new mysqli($this->dbServer,$this->dbUser,$this->dbPassword,$this->db,$this->port);

        // Only for debugging database connection
        // 
        // if ($this->mysqli->connect_errno) {
        //     echo "Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        // }
        // echo $this->mysqli->host_info . "\n";
    }



}