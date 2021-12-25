<?php

require_once('artworks.php');
require_once('databaseManager.php');

abstract class ArtworksApi {
    private static $parameters;
    private static $databaseManager;

    public static function init() {

        echo('Intentando conectar con la base de datos...');

        ArtworksApi::$databaseManager = new databaseManager();
        // if ($_POST['filters']) {
        //     // $parameters['filters'] = $_POST['filters'];
        //     // artworksApi.getFilteredArtworks();
        // } else {
        //     ArtworksApi::$databaseManager->selectAllArtworks();
        // }
        $artworksArray = ArtworksApi::$databaseManager->selectAllArtworks();
        $artworksJson = ArtworksApi::convertArrayToJson($artworksArray);
        return $artworksJson;
    }
    
    private static function getAllArtworks() {
        $artworks = artworksApi.makeUnfilteredConsult();

    }

    private static function convertArrayToJson($array) {
        return json_encode($array);
    }

    private static function getFilteredArtworks() {
        // artworksApi.makeFilteredConsult();
    }

    private static function makeFilteredConsult() {
        // TODO: Implement Consult to DDBB where obra == filters
    }

    private static function makeUnfilteredConsult() {
        // TODO: Implement Consult to DDBB where obra == filters
    }
}
ArtworksApi::init();
