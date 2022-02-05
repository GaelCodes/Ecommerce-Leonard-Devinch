<?php

// Defino el directorio backend como la constante __ROOT__
define("__ROOT__", dirname(dirname(dirname(dirname(dirname(__FILE__))))));
require_once __ROOT__ . "/loadDependencies.php";

require_once "../clientsAPI.php";
ClientsAPI::init();
ClientsAPI::login();
