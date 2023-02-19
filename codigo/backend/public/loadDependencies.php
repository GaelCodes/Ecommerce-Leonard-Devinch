<?php 

// Archivo para ejecutar las sentencias de configuración
// que necesitan ser lanzadas en el directorio root del backend
// 
// Este archivo será incluido en cada una de las APIS del backend


// Enviroment variables

require_once('vendor/autoload.php');
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__."/../private/");
$dotenv->load();