<?php

abstract class DatabaseManager {
    protected $mysqli;
    protected $dbUrl;
    protected $dbUser;
    protected $dbPassword;
    protected $dbSchema;

    function __construct(){
        // Enviroment variables
        $this->dbServer =  $_ENV['DB_SERVER_FQDN'];
        $this->dbUser = $_ENV['DB_USER'];
        $this->dbPassword = $_ENV['DB_PASSWORD'];
        $this->db = $_ENV['DB_NAME'];
        $this->port = $_ENV['DB_PORT'];
        
        // Open DDBB connection
        $this->mysqli = new mysqli($this->dbServer,$this->dbUser,$this->dbPassword,$this->db,$this->port);

        // Only for debugging database connection
        // 
        // if ($this->mysqli->connect_errno) {
        //     echo "Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        // }
        // echo $this->mysqli->host_info . "\n";
    }



}