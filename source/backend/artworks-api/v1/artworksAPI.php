<?php

require_once('artworks.php');
require_once('databaseManager.php');

abstract class ArtworksApi {
    private static $parameters;
    private static $databaseManager;

    public static function init($filters = null) {
        ArtworksApi::$databaseManager = new databaseManager();
        

        if ($filters) {
            // Return filtered artworks
        } else {
            // Return all artworks
            $artworksArray = ArtworksApi::$databaseManager->selectAllArtworks();
            $artworksJson = json_encode($artworksArray);
            echo $artworksJson;
        }
 
        // if ($_POST['filters']) {
        //     // $parameters['filters'] = $_POST['filters'];
        //     // artworksApi.getFilteredArtworks();
        // } else {
        //     ArtworksApi::$databaseManager->selectAllArtworks();
        // }
  
    }
    
    private static function getAllArtworks() {
        $artworks = artworksApi.makeUnfilteredConsult();

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
