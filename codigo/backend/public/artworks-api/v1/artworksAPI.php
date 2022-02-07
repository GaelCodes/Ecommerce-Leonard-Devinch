<?php

require_once __ROOT__ . "/public/apis-utilities/v1/artworkClass.php";
require_once __ROOT__ .
  "/public/apis-utilities/v1/artworksDatabaseManagerClass.php";
require_once __ROOT__ . "/public/apis-utilities/v1/responseClass.php";
require_once __ROOT__ . "/public/apis-utilities/v1/requestClass.php";

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
    $artworksArray = ArtworksApi::$artworksDBM->selectAllArtworks();
    $artworksJson = json_encode($artworksArray);

    $response = new Response();
    $response->setHeader("Content-Type: application/json; charset=utf-8");
    // Production Configuration
    // $response->setHeader('Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.web.app');
    $response->setHeader("Access-Control-Allow-Origin: *");
    $response->setContent($artworksJson);
    $response->send();
  }

  // Esta función obtiene el contenido del body
  // que es el objeto filters y tiene la siguiente forma:
  // filters =
  /*
  {
      "title": "Holiday",
      "ids" : [
        0 => 1,
        1 => 2,
        ...
      ],
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
}
