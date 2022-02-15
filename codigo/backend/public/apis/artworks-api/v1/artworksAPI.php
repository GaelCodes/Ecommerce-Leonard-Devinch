<?php

require_once __ROOT__ . "/common-utilities/artworkClass.php";
require_once __ROOT__ . "/common-utilities/artworksDatabaseManagerClass.php";
require_once __ROOT__ . "/common-utilities/responseClass.php";
require_once __ROOT__ . "/common-utilities/requestClass.php";

abstract class ArtworksApi
{
  private static $parameters;
  private static $artworksDBM;

  public static function init()
  {
    ArtworksApi::$artworksDBM = new ArtworksDatabaseManager();
  }

  public static function getAllArtworks()
  {
    $request = new Request();

    if ($request->getMethod() === "OPTIONS") {
      $response = new Response();

      // Production Configuration
      // $response->setHeader('Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.web.app');
      $response->setHeader(
        "Access-Control-Allow-Origin: http://127.0.0.1:5500"
      );

      $response->setHeader("Access-Control-Allow-Headers: Content-Type");
      $response->setHeader("Access-Control-Allow-Methods: POST,OPTIONS");

      $response->setHeader("Content-type: application/json; charset=utf-8");
      $response->setCode(200);
      $response->setContent('{"message" : "Message for preflights requests"}');
      $response->send();
    }

    $artworksArray = ArtworksApi::$artworksDBM->selectAllArtworks();
    $artworksJson = json_encode($artworksArray);

    $response = new Response();
    // Production Configuration
    // $response->setHeader('Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.web.app');
    $response->setHeader("Access-Control-Allow-Origin: http://127.0.0.1:5500");

    $response->setHeader("Access-Control-Allow-Headers: Content-Type");
    $response->setHeader("Access-Control-Allow-Methods: POST");

    $response->setHeader("Content-type: application/json; charset=utf-8");
    $response->setCode(200);
    $response->setContent($artworksJson);
    $response->send();
  }

  // Esta función obtiene el contenido del body
  // que es el objeto filters y tiene la siguiente forma:
  // filters =
  /*
  {
      "title": "Holiday",
      "author": "Nathalie",
      "topics": "Glass",
      "starting_date": "2021-01-24",
      "ending_date": "2021-04-15",
      "available": true,
      "dimension_x": 60,
      "dimension_y": 25,
      "price": {
      "minimum": 0,
      "maximum": 2000
    }
  */
  public static function getFilteredArtworks()
  {
    $request = new Request();

    $filters = json_decode($request->getContent(), true);

    $artworksArray = ArtworksApi::$artworksDBM->selectFilteredArtworks(
      $filters
    );

    if (!$artworksArray) {
      $response = new Response();
      $response->setHeader("Content-Type: application/json; charset=utf-8");
      // Production Configuration
      // $response->setHeader('Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.web.app');
      $response->setHeader("Access-Control-Allow-Origin: *");
      $response->setContent('{"result": false}');
      $response->send();
    } else {
      $artworksArray["result"] = true;
      $artworksJson = json_encode($artworksArray);

      $response = new Response();
      $response->setHeader("Content-Type: application/json; charset=utf-8");
      // Production Configuration
      // $response->setHeader('Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.web.app');
      $response->setHeader("Access-Control-Allow-Origin: *");
      $response->setContent($artworksJson);
      $response->send();
    }
  }

  // Esta función obtiene el contenido del body
  // que es el objeto selections y tiene la siguiente forma:
  // selections =
  /*
  [
    {
      "title": "Holiday",
      "artist_email": "Nathalie"
    },
    {
      "title": "Holiday",
      "artist_email": "Nathalie"
    }...
    
    ]
  */
  public static function getSelectedArtworks()
  {
    $request = new Request();

    if ($request->getMethod() === "OPTIONS") {
      $response = new Response();

      // Production Configuration
      // $response->setHeader('Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.web.app');
      $response->setHeader(
        "Access-Control-Allow-Origin: http://127.0.0.1:5500"
      );
      // Header for securized rutes
      $response->setHeader("Access-Control-Allow-Credentials: true");
      $response->setHeader("Access-Control-Allow-Headers: Content-Type");
      $response->setHeader("Access-Control-Allow-Methods: POST,OPTIONS");

      $response->setHeader("Content-type: application/json; charset=utf-8");
      $response->setCode(200);
      $response->setContent('{"message" : "Message for preflights requests"}');
      $response->send();
    }

    $selections = json_decode($request->getContent(), true);

    // Retrieve artworks from DB
    $artworksArray = [];
    for ($i = 0; $i < count($selections); $i++) {
      $title = $selections[$i]["title"];
      $artist_email = $selections[$i]["artistEmail"];

      $artwork = ArtworksApi::$artworksDBM->selectArtworkByPK(
        $title,
        $artist_email
      );

      $artwork = $artwork->toArray();

      $artworksArray[$i] = $artwork;
    }

    // Send artworks to Frontend
    $artworksJson = json_encode($artworksArray);

    $response = new Response();
    $response->setHeader("Access-Control-Allow-Origin: http://127.0.0.1:5500");
    $response->setHeader("Content-Type: application/json; charset=utf-8");
    $response->setHeader("Access-Control-Allow-Methods: POST");
    // Header for securized rutes
    $response->setHeader("Access-Control-Allow-Credentials: true");
    $response->setHeader("Access-Control-Allow-Headers: Content-Type");

    // Production Configuration
    // $response->setHeader('Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.web.app');

    $response->setCode(200);
    $response->setContent($artworksJson);
    $response->send();
  }
}
