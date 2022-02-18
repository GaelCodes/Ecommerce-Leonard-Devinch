<?php

require_once __ROOT__ . "/common-utilities/clientClass.php";

require_once __ROOT__ . "/common-utilities/clientsDatabaseManagerClass.php";

require_once __ROOT__ . "/common-utilities/artworksDatabaseManagerClass.php";

require_once __ROOT__ . "/common-utilities/orderClass.php";
require_once __ROOT__ . "/common-utilities/ordersDatabaseManagerClass.php";

require_once __ROOT__ . "/common-utilities/purchasedArtworkClass.php";

require_once __ROOT__ .
  "/common-utilities/purchasedArtworksDatabaseManagerClass.php";

require_once __ROOT__ . "/apis/apis-utilities/v1/paymentManagerClass.php";
require_once __ROOT__ . "/common-utilities/firebaseStorageManagerClass.php";

require_once __ROOT__ . "/common-utilities/responseClass.php";
require_once __ROOT__ . "/common-utilities/requestClass.php";

abstract class ClientsAPI
{
  public static function register()
  {
    $request = new Request();

    if ($request->getMethod() === "OPTIONS") {
      $response = new Response();
      //$response->setHeader("Content-Type: application/json");
      // Production Configuration
      // $response->setHeader('Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.web.app');
      $response->setHeader(
        "Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.abigaelheredia.es"
      );
      $response->setHeader("Access-Control-Allow-Headers: Content-Type");
      $response->setHeader("Access-Control-Allow-Methods: POST,OPTIONS");

      $response->setHeader("Content-type: application/json; charset=utf-8");
      $response->setCode(200);
      $response->setContent('{"message" : "Message for preflights requests"}');
      $response->send();
    }

    $clientData = json_decode($request->getContent(), true);

    //error_log("Hey! esto estoy recibiendo desde FRONTED CON SERVER");
    //error_log(print_r($_SERVER, true));

    //error_log("Hey! esto estoy recibiendo desde FRONTED CON PHPINPUT");
    //error_log($clientData);

    $clientData["password"] = password_hash(
      $clientData["password"],
      PASSWORD_DEFAULT
    );

    $client = new Client(
      null,
      $clientData["client_email"],
      null,
      $clientData["password"],
      @$clientData["full_name"],
      null,
      @$clientData["telephone_number"]
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
    // Production Configuration
    // $response->setHeader('Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.web.app');
    $response->setHeader(
      "Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.abigaelheredia.es"
    );
    $response->setHeader("Access-Control-Allow-Headers: Content-Type");
    $response->setHeader("Access-Control-Allow-Methods: POST");

    $response->setHeader("Content-type: application/json; charset=utf-8");
    $response->setCode($code);
    $response->setContent($message);
    $response->send();
  }

  public static function login()
  {
    $request = new Request();

    if ($request->getMethod() === "OPTIONS") {
      $response = new Response();

      // Production Configuration
      // $response->setHeader('Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.web.app');
      // $response->setHeader(
      //   "Access-Control-Allow-Origin: http://127.0.0.1:5500"
      // );
      $response->setHeader(
        "Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.abigaelheredia.es"
      );
      // Header for securized rutes
      $response->setHeader("Access-Control-Allow-Credentials: true");
      $response->setHeader("Access-Control-Allow-Headers: Content-Type");
      $response->setHeader("Access-Control-Expose-Headers: Set-Cookie");
      $response->setHeader("Access-Control-Allow-Methods: POST,OPTIONS");

      $response->setHeader("Content-type: application/json; charset=utf-8");
      $response->setCode(200);
      $response->setContent('{"message" : "Message for preflights requests"}');
      $response->send();
    }

    $clientData = json_decode($request->getContent(), true);
    $password = $clientData["password"];
    $client_email = $clientData["client_email"];

    // Retrieve client from database if credentials are valid
    $client = Client::validate_credentials($client_email, $password);

    if ($client) {
      $JWT = Client::generateJWT($client);

      // setcookie("jwt-cookie", $JWT, [
      //   "secure" => true,
      //   "samesite" => "None",
      //   "path" => "/",
      //   // This parameter will depend if Remember me is set or not
      //   //"expires" => time() + 60 * 60 * 24 * 30,
      // ]);
      setcookie("jwt-cookie", $JWT, [
        "secure" => true,
        "SameSite" => "None",
        "domain" => "backend.ecommerce-leonard-devinch.abigaelheredia.es",
        "path" => "/",
        // This parameter will depend if Remember me is set or not
        "expires" => time() + 60 * 60 * 24 * 30,
      ]);

      $code = 200;
      $message = [
        "message" => "Logged succeessfully",
        "userData" => [
          "email" => $client->get_client_email(),
          "fullName" => $client->get_full_name(),
          "shippingAddress" => $client->get_shipping_address(),
          "telephoneNumber" => $client->get_telephone_number(),
        ],
      ];

      $message = json_encode($message);
    } else {
      $code = 401;
      $message = '{ "message":  "Invalid credentials"}';
    }

    $response = new Response();

    // Production Configuration
    // $response->setHeader('Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.web.app');
    // $response->setHeader("Access-Control-Allow-Origin: http://127.0.0.1:5500");
    $response->setHeader(
      "Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.abigaelheredia.es"
    );
    // Headers for securized rutes
    $response->setHeader("Access-Control-Allow-Credentials: true");
    $response->setHeader("Access-Control-Expose-Headers: Set-Cookie");
    header(
      "Access-Control-Allow-Headers: Set-Cookie, Access-Control-Expose-Headers, Access-Control-Allow-Origin"
    );

    $response->setHeader("Access-Control-Allow-Headers: Content-Type");
    $response->setHeader("Access-Control-Allow-Methods: POST");

    $response->setHeader("Content-type: application/json; charset=utf-8");
    $response->setCode($code);
    $response->setContent($message);
    $response->send();
  }

  public static function logout()
  {
    $request = new Request();

    if ($request->getMethod() === "OPTIONS") {
      $response = new Response();

      // Production Configuration
      // $response->setHeader('Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.web.app');
      $response->setHeader(
        "Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.abigaelheredia.es"
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
    ClientsAPI::authentication_process($request);

    setcookie("jwt-cookie", "", [
      "secure" => true,
      "SameSite" => "None",
      "domain" => "backend.ecommerce-leonard-devinch.abigaelheredia.es",
      "path" => "/",
      // This parameter will depend if Remember me is set or not
      "expires" => time() - 60 * 60 * 24 * 30,
    ]);

    $response = new Response();

    // Production Configuration
    // $response->setHeader('Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.web.app');
    $response->setHeader(
      "Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.abigaelheredia.es"
    );
    // Headers for securized rutes
    $response->setHeader("Access-Control-Allow-Credentials: true");

    $response->setHeader("Access-Control-Allow-Headers: Content-Type");
    $response->setHeader("Access-Control-Allow-Methods: POST");

    $response->setHeader("Content-type: application/json; charset=utf-8");
    $response->setCode(200);
    $response->setContent('{"message" : "Logged out succeessfully"}');
    $response->send();
  }

  public static function update_profile()
  {
    $request = new Request();

    if ($request->getMethod() === "OPTIONS") {
      $response = new Response();

      $response->setHeader("Access-Control-Allow-Credentials: true");
      $response->setHeader(
        "Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.abigaelheredia.es"
      );
      $response->setHeader("Access-Control-Allow-Headers: Content-Type");
      $response->setHeader("Access-Control-Allow-Methods: POST,OPTIONS");

      $response->setHeader("Content-type: application/json; charset=utf-8");
      $response->setCode(200);
      $response->setContent('{"message" : "Message for preflights requests"}');
      $response->send();
    }
    ClientsAPI::authentication_process($request);

    $updated_client_data = json_decode($request->getContent(), true);

    // Retrieve client from DDBB
    $JWT = Client::decodeJWT($request->getCookie("jwt-cookie"));
    $client_id = $JWT["payload"]["client_id"];

    $clientsDBM = new ClientsDatabaseManager();
    $client = $clientsDBM->select_client_by_id($client_id);

    // Update client
    $client->set_full_name($updated_client_data["full_name"]);
    $client->set_telephone_number(
      floatval($updated_client_data["telephone_number"])
    );
    $client->set_shipping_address($updated_client_data["shipping_address"]);

    // Insert updated client
    $clientsDBM->update_client($client);

    // Generate Response
    $response = new Response();

    $code = 200;

    $message = '{ "message" : "Perfil de usuario actualizado correctamente" }';

    $response->setHeader(
      "Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.abigaelheredia.es"
    );
    // Headers for securized rutes
    $response->setHeader("Access-Control-Allow-Credentials: true");
    header(
      "Access-Control-Allow-Headers: Set-Cookie, Access-Control-Expose-Headers, Access-Control-Allow-Origin"
    );

    $response->setHeader("Access-Control-Allow-Headers: Content-Type");
    $response->setHeader("Access-Control-Allow-Methods: POST");

    $response->setHeader("Content-type: application/json; charset=utf-8");
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
      "artistEmail": "hola@asd.com",
      "title" : "mi obra favorita",
      "units" : 10
    },
    { 
      "artistEmail": "hola@asd.com",
      "title" : "mi obra favorita",
      "units" : 5
    },
    ...
  ]
  */
  public static function make_order()
  {
    // Authentication required
    $request = new Request();
    if ($request->getMethod() === "OPTIONS") {
      $response = new Response();

      // Production Configuration
      // $response->setHeader('Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.web.app');
      $response->setHeader(
        "Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.abigaelheredia.es"
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
    ClientsAPI::authentication_process($request);

    // Data adquisition
    $purchasedArtworks = json_decode($request->getContent(), true);
    $artworksDBM = new ArtworksDatabaseManager();
    for ($i = 0; $i < count($purchasedArtworks); $i++) {
      $units = $purchasedArtworks[$i]["units"];

      $filters["title"] = $purchasedArtworks[$i]["title"];
      $filters["artist_email"] = $purchasedArtworks[$i]["artistEmail"];

      $artwork = $artworksDBM->selectFilteredArtworks($filters)[0];
      // TODO: Comprobrar que la artwork tiene suficientes units

      $purchasedArtworks[$i] = new PurchasedArtwork(
        $artwork->get_title(),
        null,
        $artwork->get_price(),
        $units,
        null,
        $artwork->get_artist()
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
    // Production Configuration
    // $response->setHeader('Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.web.app');
    $response->setHeader(
      "Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.abigaelheredia.es"
    );
    // Header for securized rutes
    $response->setHeader("Access-Control-Allow-Credentials: true");

    $response->setHeader("Access-Control-Allow-Headers: Content-Type");
    $response->setHeader("Access-Control-Allow-Methods: POST");

    $response->setHeader("Content-type: application/json; charset=utf-8");

    $response->setCode($code);
    $response->setContent($message);
    $response->send();
  }

  public static function consult_orders()
  {
    // Authentication required

    $request = new Request();
    if ($request->getMethod() === "OPTIONS") {
      $response = new Response();

      // Production Configuration
      // $response->setHeader('Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.web.app');
      $response->setHeader(
        "Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.abigaelheredia.es"
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
    ClientsAPI::authentication_process($request);

    // Retrieve user data
    $JWT = Client::decodeJWT($request->getCookie("jwt-cookie"));
    $client_id = $JWT["payload"]["client_id"];

    $clientDBM = new ClientsDatabaseManager();
    $client = $clientDBM->select_client_by_id($client_id);

    // Retrieve data from DDBB
    $ordersDBM = new OrdersDatabaseManager();

    // TODO: Develop select_orders....
    $client_orders = $ordersDBM->select_orders_by_client($client);

    // Format data
    $orders_as_array = [];
    for ($i = 0; $i < count($client_orders); $i++) {
      $order_as_array = $client_orders[$i]->to_array();
      $orders_as_array[$i] = $order_as_array;
    }

    // Generate response
    $code = 200;
    $message = [
      "message" => "Todo correcto aquí tienes tus orders",
      "ordersData" => $orders_as_array,
    ];

    $message = json_encode($message, true);
    $response = new Response();
    // Production Configuration
    // $response->setHeader('Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.web.app');
    $response->setHeader(
      "Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.abigaelheredia.es"
    );
    // Header for securized rutes
    $response->setHeader("Access-Control-Allow-Credentials: true");

    $response->setHeader("Access-Control-Allow-Headers: Content-Type");
    $response->setHeader("Access-Control-Allow-Methods: POST");

    $response->setHeader("Content-type: application/json; charset=utf-8");

    $response->setCode($code);
    $response->setContent($message);
    $response->send();
  }

  public static function download_order()
  {
    // Authentication required

    $request = new Request();
    if ($request->getMethod() === "OPTIONS") {
      $response = new Response();

      // Production Configuration
      // $response->setHeader('Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.web.app');
      $response->setHeader(
        "Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.abigaelheredia.es"
      );
      // Header for securized rutes
      $response->setHeader("Access-Control-Allow-Credentials: true");
      $response->setHeader("Access-Control-Allow-Headers: Content-Type");
      $response->setHeader("Access-Control-Allow-Methods: POST,OPTIONS");

      $response->setHeader("Content-Type: application/json");
      $response->setCode(200);
      $response->setContent('{"message" : "Message for preflights requests"}');
      $response->send();
    }
    ClientsAPI::authentication_process($request);

    // Retrieve user data
    $JWT = Client::decodeJWT($request->getCookie("jwt-cookie"));
    $client_id = $JWT["payload"]["client_id"];

    $clientDBM = new ClientsDatabaseManager();
    $client = $clientDBM->select_client_by_id($client_id);

    // Retrieve order
    $dataArray = json_decode($request->getContent(), true);
    error_log("Datos recibidos: " . $request->getContent());
    error_log("Datos recibidos en array: ");
    error_log(print_r($dataArray, true));

    $order_data = json_decode($request->getContent(), true);
    $order_id = $order_data["order_id"];

    $orderDBM = new OrdersDatabaseManager();
    $order = $orderDBM->select_order_by_client_and_id($client, $order_id);

    // Download PDF from bucket users/{client_email}/order-{ID}.pdf
    $firebaseSM = new FirebaseStorageManager();
    $fichero = $firebaseSM->download_pdf($client, $order);

    // $fichero = realpath(__DIR__ . "/original.pdf");
    // Send PDF
    error_log("Esta el fichero aqui???  :" . $fichero);

    $response = new Response();
    if (file_exists($fichero)) {
      error_log("El fichero SI está aquí  :" . $fichero);
      // Generate response
      $code = 200;
      $response->setHeader(
        "Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.abigaelheredia.es"
      );
      // Header for securized rutes
      $response->setHeader("Access-Control-Allow-Credentials: true");
      $response->setHeader("Access-Control-Allow-Headers: Content-Type");
      $response->setHeader("Access-Control-Allow-Methods: POST");

      $response->setHeader("Content-Description: File Transfer");
      $response->setHeader("Content-Type: application/pdf");
      $response->setHeader(
        'Content-Disposition: attachment; filename="' . basename($fichero) . '"'
      );
      $response->setHeader(
        "Access-Control-Expose-Headers: Content-Type,Content-Disposition"
      );
      $response->setHeader("Expires: 0");
      $response->setHeader("Cache-Control: must-revalidate");
      $response->setHeader("Pragma: public");
      $response->setHeader("Content-Length: " . filesize($fichero));
      $response->setCode($code);

      readfile($fichero);
      // TODO: Delete file when downloaded
      exit();
    } else {
      error_log("El fichero no está aquí  :" . $fichero);
      $response->setHeader(
        "Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.abigaelheredia.es"
      );
      $response->setHeader("Access-Control-Allow-Credentials: true");
      $response->setHeader("Access-Control-Allow-Headers: Content-Type");
      $response->setHeader("Access-Control-Allow-Methods: POST");
      $response->setHeader("Content-type: application/json; charset=utf-8");

      $code = 500;
      $message = '{"message" : "El fichero no existe"}';
      $response->setCode($code);
      $response->setContent($message);
      $response->send();
    }
  }

  private static function expulse_user()
  {
    $code = 401;
    $message = '{ "message":  "Invalid credentials"}';

    $response = new Response();

    // Production Configuration
    // $response->setHeader('Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.web.app');
    $response->setHeader(
      "Access-Control-Allow-Origin: https://ecommerce-leonard-devinch.abigaelheredia.es"
    );
    // Header for securized rutes
    $response->setHeader("Access-Control-Allow-Credentials: true");
    $response->setHeader("Access-Control-Allow-Headers: Content-Type");
    $response->setHeader("Access-Control-Allow-Methods: POST,OPTIONS");

    $response->setHeader("Content-type: application/json; charset=utf-8");

    $response->setCode($code);
    $response->setContent($message);
    $response->send();
  }

  private static function authentication_process(Request $request = null)
  {
    $JWT = $request->getCookie("jwt-cookie");

    if (!$JWT) {
      error_log("Usuario sin cookie");
      ClientsAPI::expulse_user();
    }

    $valid = Client::validate_JWT($JWT);
    if (!$valid) {
      error_log("Usuario con cookie no valida");
      ClientsAPI::expulse_user();
    }
  }
}
