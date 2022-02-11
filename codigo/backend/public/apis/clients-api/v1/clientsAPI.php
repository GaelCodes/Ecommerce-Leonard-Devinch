<?php

require_once __ROOT__ . "/public/apis-utilities/v1/clientClass.php";

require_once __ROOT__ .
  "/public/apis-utilities/v1/clientsDatabaseManagerClass.php";

require_once __ROOT__ .
  "/public/apis-utilities/v1/artworksDatabaseManagerClass.php";

require_once __ROOT__ . "/public/apis-utilities/v1/orderClass.php";
require_once __ROOT__ .
  "/public/apis-utilities/v1/ordersDatabaseManagerClass.php";

require_once __ROOT__ . "/public/apis-utilities/v1/purchasedArtworkClass.php";

require_once __ROOT__ .
  "/public/apis-utilities/v1/purchasedArtworksDatabaseManagerClass.php";

require_once __ROOT__ . "/public/apis-utilities/v1/paymentManagerClass.php";

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
      null,
      $clientData["client_email"],
      null,
      $clientData["password"],
      $clientData["full_name"],
      $clientData["shipping_address"],
      $clientData["telephone_number"]
    );

    try {
      $clientDBM = new ClientsDatabaseManager();
      $clientDBM->insert_client($client);

      // The stripe customer must be created after client is created
      // in our database we ensure this way that client data fits
      // our database relationships
      $paymentManager = new PaymentManager();
      $created_customer = $paymentManager->create_customer($client);

      $client->set_stripe_customer_id($created_customer["id"]);
      $clientDBM->update_client($client);

      $message = '{"message" : "User register succeessfully"}';
      $code = 200;
    } catch (\Exception $th) {
      $message =
        '{"message" : "Client couldn\'t be registered in Database or Customer couldn\'t be registered in Stripe"}';
      $code = 500;
    }

    $response = new Response();
    $response->setHeader("Content-Type: application/json; charset=utf-8");
    // Production Configuration
    // $response->setHeader('Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.web.app');
    $response->setHeader("Access-Control-Allow-Origin: *");
    $response->setCode($code);
    $response->setContent($message);
    $response->send();
  }

  public static function login()
  {
    $request = new Request();

    $clientData = json_decode($request->getContent(), true);
    $password = $clientData["password"];
    $client_email = $clientData["client_email"];

    $client = Client::validate_credentials($client_email, $password);

    if ($client) {
      $JWT = Client::generateJWT($client);
      setcookie("jwt-cookie", $JWT);

      $code = 200;
      $message = '{ "message":  "Logged succeessfully"}';
    } else {
      $code = 401;
      $message = '{ "message":  "Invalid credentials"}';
    }

    $response = new Response();
    $response->setHeader("Content-Type: application/json; charset=utf-8");
    // Production Configuration
    // $response->setHeader('Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.web.app');
    $response->setHeader("Access-Control-Allow-Origin: *");
    $response->setCode($code);
    $response->setContent($message);
    $response->send();
  }

  // Esta función obtiene el contenido del body
  // que es el objeto orderData y tiene la siguiente forma:
  // purchasedArtworks =
  /*
  [
    { 
      "id" : 1,
      "units" : 10
    },
    { 
      "id" : 2,
      "units" : 5
    },
    ...
  ]
  */
  public static function make_order()
  {
    // Authentication required
    $request = new Request();
    ClientsAPI::authentication_process($request);

    // Data adquisition
    $purchasedArtworks = json_decode($request->getContent(), true);
    $artworksDBM = new ArtworksDatabaseManager();
    for ($i = 0; $i < count($purchasedArtworks); $i++) {
      $units = $purchasedArtworks[$i]["units"];

      $filters["ids"][0] = $purchasedArtworks[$i]["id"];
      $artwork = $artworksDBM->selectFilteredArtworks($filters)[0];

      $purchasedArtworks[$i] = new PurchasedArtwork(
        $artwork["title"],
        $artwork["artist"]["artist_email"],
        $artwork["price"],
        $units
      );
    }

    $JWT = Client::decodeJWT($request->getCookie("jwt-cookie"));
    $client_id = $JWT["payload"]["client_id"];

    $clientsDBM = new ClientsDatabaseManager();
    $client = $clientsDBM->select_client_by_id($client_id);

    try {
      // Insertion to DB
      $order = new Order($client, $purchasedArtworks);
      $ordersDBM = new OrdersDatabaseManager();
      $order = $ordersDBM->insert_order($order);

      $purchasedArtworksDBM = new PurchasedArtworksDatabaseManager();
      for ($i = 0; $i < count($purchasedArtworks); $i++) {
        $purchasedArtworks[$i]->set_order_id($order->get_order_id());
        $purchasedArtworksDBM->insert_purchased_artwork($purchasedArtworks[$i]);
      }

      // PaymentIntent Generation
      $paymentManager = new PaymentManager();
      $paymentManager->create_payment_intent($client, $order);
      $client_secret = $paymentManager->get_client_secret();
    } catch (\Throwable $th) {
      throw $th;
      //throw new Exception("Error Inserting Order To Database", 1);
    }

    // Response generation
    $code = 200;
    $message = [
      "message" => "Everything is ok, order made succeessfully!",
      "client_secret" => $client_secret,
    ];
    $message = json_encode($message);

    $response = new Response();
    $response->setHeader("Content-Type: application/json; charset=utf-8");
    // Production Configuration
    // $response->setHeader('Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.web.app');
    $response->setHeader("Access-Control-Allow-Origin: *");
    $response->setCode($code);
    $response->setContent($message);
    $response->send();
  }

  public static function consult_orders()
  {
    // Authentication required
  }

  private static function expulse_user()
  {
    $code = 401;
    $message = '{ "message":  "Invalid credentials"}';

    $response = new Response();
    $response->setHeader("Content-Type: application/json; charset=utf-8");
    // Production Configuration
    // $response->setHeader('Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.web.app');
    $response->setHeader("Access-Control-Allow-Origin: *");
    $response->setCode($code);
    $response->setContent($message);
    $response->send();
  }

  private static function authentication_process(Request $request = null)
  {
    $JWT = $request->getCookie("jwt-cookie");
    if (!$JWT) {
      ClientsAPI::expulse_user();
    }

    $valid = Client::validate_JWT($JWT);
    if (!$valid) {
      ClientsAPI::expulse_user();
    }
  }
}
