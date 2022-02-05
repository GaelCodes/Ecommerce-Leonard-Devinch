<?php

require_once __ROOT__ .
  "/public/apis-utilities/v1/clientsDatabaseManagerClass.php";

class Client
{
  private $client_email;
  private $password;
  private $full_name;
  private $shipping_address;
  private $telephone_number;

  public function __construct(
    $client_email,
    $password = null,
    $full_name = null,
    $shipping_address = null,
    $telephone_number = null
  ) {
    $this->client_email = $client_email;
    $this->password = $password;
    $this->full_name = $full_name;
    $this->shipping_address = $shipping_address;
    $this->telephone_number = $telephone_number;
  }

  public function get_email()
  {
    return $this->client_email;
  }
  public function get_full_name()
  {
    return $this->full_name;
  }
  public function get_password()
  {
    return $this->password;
  }
  public function get_telephone_number()
  {
    return $this->telephone_number;
  }

  public function get_shipping_address()
  {
    return $this->shipping_address;
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

    $clientDBM = new ClientsDatabaseManager();
    $client_id = $clientDBM->get_client_id($this->client_email);

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
}
