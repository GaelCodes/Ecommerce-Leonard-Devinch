<?php

error_log("Hola desde el backend");
// __ROOT__ será el directorio backend
define("__ROOT__", dirname(dirname(dirname(dirname(dirname(__FILE__))))));
require_once __ROOT__ . "/loadDependencies.php";

require_once __DIR__."/../artworksAPI.php";
ArtworksApi::init();
ArtworksApi::getAllArtworks();
