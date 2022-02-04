<?php
// Production headers configuration
// header('Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.web.app/');

// Development headers configuration
//
// header('Access-Control-Allow-Origin: *');
// header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
// header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
// header("Allow: GET, POST, OPTIONS, PUT, DELETE");
// $method = $_SERVER['REQUEST_METHOD'];
// if($method == "OPTIONS") {
//     die();
// }

define("__ROOT__", dirname(dirname(dirname(dirname(dirname(__FILE__))))));
require_once __ROOT__ . "/loadDependencies.php";

require_once "../artworksAPI.php";
ArtworksApi::init();
ArtworksApi::getFilteredArtworks();
