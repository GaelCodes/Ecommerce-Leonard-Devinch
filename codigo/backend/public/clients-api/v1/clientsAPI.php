<?php

require_once __ROOT__ . "/public/apis-utilities/v1/clientClass.php";
require_once __ROOT__ .
  "/public/apis-utilities/v1/clientsDatabaseManagerClass.php";
require_once __ROOT__ . "/public/apis-utilities/v1/responseClass.php";
require_once __ROOT__ . "/public/apis-utilities/v1/requestClass.php";

abstract class ClientsAPI
{
  public static function init()
  {
  }

  public static function register()
  {
    $request = new Request();

    $clientData = json_decode($request->getContent(), true);

    $clientData["password"] = password_hash(
      $clientData["password"],
      PASSWORD_DEFAULT
    );

    $client = new Client(
      $clientData["client_email"],
      $clientData["password"],
      $clientData["full_name"],
      $clientData["shipping_address"],
      $clientData["telephone_number"]
    );

    $clientDBM = new ClientsDatabaseManager();
    $result = $clientDBM->insert_client($client);

    $response = new Response();
    $response->setHeader("Content-Type: application/json; charset=utf-8");
    // Production Configuration
    // $response->setHeader('Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.web.app');
    $response->setHeader("Access-Control-Allow-Origin: *");

    if ($result) {
      $message = '{"message" : "User register succeessfully"}';
      $response->setCode(200);
    } else {
      $message = '{"message" : "User couldn\'t be registered"}';
      $response->setCode(500);
    }

    $response->setContent($message);
    $response->send();
  }

  public static function login()
  {
  }
  public static function make_order()
  {
  }

  public static function consult_order()
  {
  }
}
