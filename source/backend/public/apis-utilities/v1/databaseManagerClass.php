<?php

abstract class DatabaseManager {
    protected $mysqli;
    protected $DB_SERVER_FQDN;
    protected $DB_PORT;
    protected $DB;
    protected $DB_USER;
    protected $DB_PASSWORD;

    function __construct(){


        
        // Enviroment variables
        $this->DB_SERVER_FQDN =  $_ENV['DB_SERVER_FQDN'];
        $this->DB_PORT = $_ENV['DB_PORT'];
        $this->DB = $_ENV['DB_NAME'];
        $this->DB_USER = $_ENV['DB_USER'];
        $this->DB_PASSWORD = $_ENV['DB_PASSWORD'];
        var_dump($this);
        
        // Open DDBB connection
        $this->mysqli = new mysqli($this->DB_SERVER_FQDN,$this->DB_USER,$this->DB_PASSWORD,$this->DB,$this->DB_PORT);

        // Only for debugging database connection
        // 
        // if ($this->mysqli->connect_errno) {
        //     echo "Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        // }
        // echo $this->mysqli->host_info . "\n";
    }



}