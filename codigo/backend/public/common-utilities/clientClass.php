<?php

require_once "clientsDatabaseManagerClass.php";

class Client
{
  private ?int $client_id;
  private string $client_email;
  private string $password;
  private ?string $full_name;
  private ?string $shipping_address;
  private ?float $telephone_number;
  private ?string $stripe_customer_id;

  public function __construct(
    int $client_id = null,
    string $client_email,
    string $stripe_customer_id = null,
    string $password = null,
    string $full_name = null,
    string $shipping_address = null,
    float $telephone_number = null
  ) {
    $this->client_id = $client_id;
    $this->client_email = $client_email;
    $this->password = $password;
    $this->full_name = $full_name;
    $this->shipping_address = $shipping_address;
    $this->telephone_number = $telephone_number;
    $this->stripe_customer_id = $stripe_customer_id;
  }

  public function get_client_email(): string
  {
    return $this->client_email;
  }

  public function get_full_name(): ?string
  {
    return $this->full_name;
  }

  public function get_password(): string
  {
    return $this->password;
  }

  public function get_telephone_number(): ?float
  {
    return $this->telephone_number;
  }

  public function get_shipping_address(): ?string
  {
    return $this->shipping_address;
  }

  public function get_client_id(): int
  {
    return $this->client_id;
  }

  public function set_stripe_customer_id(string $stripe_customer_id)
  {
    $this->stripe_customer_id = $stripe_customer_id;
  }

  public function get_stripe_customer_id(): ?string
  {
    return $this->stripe_customer_id;
  }

  public static function validate_credentials(
    string $client_email,
    string $password
  ) {
    $clientDBM = new ClientsDatabaseManager();
    $client = $clientDBM->select_client_by_email($client_email);

    $passwordHash = $client->get_password();

    $valid = password_verify($password, $passwordHash);

    if ($valid) {
      return $client;
    } else {
      throw new Exception(
        "Error invalid credentials in credentials validation",
        1
      );
    }
  }

  public static function generateJWT(Client $client)
  {
    // Objective = "header.payload.signature"
    $header = json_encode(["typ" => "JWT", "alg" => "HS256"]);
    $payload = json_encode(["client_id" => $client->get_client_id()]);

    // base64URL must be use because the generated string
    // might be used in a URL (eg; when trying to authenticated using GET method)
    // and the browser may misinterpreted some characters of Base64 character set

    $base64UrlHeader = str_replace(
      ["+", "/", "="],
      ["-", "_", ""],
      base64_encode($header)
    );
    $base64UrlPayload = str_replace(
      ["+", "/", "="],
      ["-", "_", ""],
      base64_encode($payload)
    );

    $signature = hash_hmac(
      "sha256",
      $base64UrlHeader . "." . $base64UrlPayload,
      $_ENV["JWT_KEY"],
      true
    );

    $base64UrlSignature = str_replace(
      ["+", "/", "="],
      ["-", "_", ""],
      base64_encode($signature)
    );

    $JWT =
      $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

    return $JWT;
  }

  public static function validate_JWT(string $JWT)
  {
    $JWT = Client::JWTtoArray($JWT);
    $base64UrlHeader = $JWT["header"];
    $base64UrlPayload = $JWT["payload"];
    $base64UrlSignature = $JWT["signature"];

    $base64Signature = str_replace(["-", "_"], ["+", "/"], $base64UrlSignature);
    $signature_value = base64_decode($base64Signature);

    $signature_expected = hash_hmac(
      "sha256",
      $base64UrlHeader . "." . $base64UrlPayload,
      $_ENV["JWT_KEY"],
      true
    );

    if (hash_equals($signature_expected, $signature_value)) {
      return true;
    } else {
      return false;
    }
  }

  public static function decodeJWT(string $JWT)
  {
    $JWT = Client::JWTtoArray($JWT);

    $base64UrlHeader = $JWT["header"];
    $base64Header = str_replace(["-", "_"], ["+", "/"], $base64UrlHeader);
    $header = base64_decode($base64Header);

    $base64UrlPayload = $JWT["payload"];
    $base64Payload = str_replace(["-", "_"], ["+", "/"], $base64UrlPayload);
    $payload = base64_decode($base64Payload);

    $base64UrlSignature = $JWT["signature"];
    $base64Signature = str_replace(["-", "_"], ["+", "/"], $base64UrlSignature);
    $signature = base64_decode($base64Signature);

    $JWT["header"] = json_decode($header, true);
    $JWT["payload"] = json_decode($payload, true);
    $JWT["signature"] = json_decode($signature);

    return $JWT;
  }

  public static function JWTtoArray($JWT)
  {
    $JWTarrayAux = explode(".", $JWT);

    $JWTarray["header"] = $JWTarrayAux[0];
    $JWTarray["payload"] = $JWTarrayAux[1];
    $JWTarray["signature"] = $JWTarrayAux[2];

    return $JWTarray;
  }
}
