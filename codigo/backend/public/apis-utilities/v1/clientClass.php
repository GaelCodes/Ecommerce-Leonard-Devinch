<?php

require_once __ROOT__ .
  "/public/apis-utilities/v1/clientsDatabaseManagerClass.php";

class Client
{
  private int $id;
  private string $client_email;
  private string $password;
  private string $full_name;
  private string $shipping_address;
  private float $telephone_number;
  private ?string $stripe_customer_id;

  public function __construct(
    string $client_email,
    string $password = null,
    string $full_name = null,
    string $shipping_address = null,
    float $telephone_number = null,
    string $stripe_customer_id = null
  ) {
    $this->client_email = $client_email;
    $this->password = $password;
    $this->full_name = $full_name;
    $this->shipping_address = $shipping_address;
    $this->telephone_number = $telephone_number;
    $this->stripe_customer_id = $stripe_customer_id;
  }

  public function get_email(): string
  {
    return $this->client_email;
  }

  public function get_full_name(): string
  {
    return $this->full_name;
  }

  public function get_password(): string
  {
    return $this->password;
  }

  public function get_telephone_number(): float
  {
    return $this->telephone_number;
  }

  public function get_shipping_address(): string
  {
    return $this->shipping_address;
  }

  public function get_id()
  {
    if (is_null($this->id)) {
      $clientDBM = new ClientsDatabaseManager();
      $this->id = $clientDBM->get_client_id($this->client_email);
    }

    return $this->id;
  }

  public function set_stripe_customer_id(string $stripe_customer_id)
  {
    $this->stripe_customer_id = $stripe_customer_id;
  }

  public function get_stripe_customer_id(): ?string
  {
    return $this->stripe_customer_id;
  }

  public function validate_credentials(string $password)
  {
    $clientDBM = new ClientsDatabaseManager();

    $passwordHash = $clientDBM->get_password_hash($this->client_email);
    return password_verify($password, $passwordHash);
  }

  public function generateJWT()
  {
    // Objective = "header.payload.signature"
    $client_id = $this->get_id();

    $header = json_encode(["typ" => "JWT", "alg" => "HS256"]);
    $payload = json_encode(["client_id" => $client_id]);

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
