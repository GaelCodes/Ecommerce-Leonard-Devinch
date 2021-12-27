<?php

require_once('../../apis-utilities/v1/artworkClass.php');
require_once('../../apis-utilities/v1/artworksDatabaseManagerClass.php');
require_once('../../apis-utilities/v1/responseClass.php');

abstract class ArtworksApi {
    private static $parameters;
    private static $artworksDatabaseManager;

    public static function init($filters = null) {
        ArtworksApi::$artworksDatabaseManager = new ArtworksDatabaseManager();
        

        if ($filters) {
            //TODO: Return filtered artworks

            // $parameters['filters']
            // artworksApi.getFilteredArtworks();
            // } else {
            //     ArtworksApi::$databaseManager->selectAllArtworks();
            // }
        } else {
            // Return all artworks
            ArtworksApi::getAllArtworks();
        }
 

  
    }
    
    private static function getAllArtworks() {
        $artworksArray = ArtworksApi::$artworksDatabaseManager->selectAllArtworks();
        $artworksJson = json_encode($artworksArray);
        
        
        $response = new Response();
        $response->setContent($artworksJson);
        $response->setHeader('Content-Type: application/json; charset=utf-8');
        $response->send();

    }

    private static function getFilteredArtworks() {
        // artworksApi.makeFilteredConsult();
    }
}
ArtworksApi::init();
