<?php

// Archivo para ejecutar las sentencias de configuración
// que necesitan ser lanzadas en el directorio root del webhook
//

require_once "vendor/autoload.php";

// Initialize enviroment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
