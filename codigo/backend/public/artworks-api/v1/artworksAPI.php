<?php

require_once __ROOT__ . "/public/apis-utilities/v1/artworkClass.php";
require_once __ROOT__ .
  "/public/apis-utilities/v1/artworksDatabaseManagerClass.php";
require_once __ROOT__ . "/public/apis-utilities/v1/responseClass.php";

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

  public static function getFilteredArtworks()
  {
    $filters = [
      "author" => "hola",
      "starting_date" => "",
      "ending_date" => "",
    ];

    $artworksArray = ArtworksApi::$artworksDBM->selectFilteredArtworks(
      $filters
    );

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
