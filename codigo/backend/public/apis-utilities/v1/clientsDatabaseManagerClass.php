<?php
require_once "databaseManagerClass.php";

class ClientsDatabaseManager extends DatabaseManager
{
  public function __construct()
  {
    parent::__construct();
  }

  public function insert_client(Client $client)
  {
    var_dump($client);

    $stmt = $this->mysqli->prepare(
      "INSERT INTO CLIENTS(client_email,full_name,password,telephone_number,shipping_address) VALUES(?,?,?,?,?)"
    );

    $stmt->bind_param(
      "sssis",
      $client_email,
      $full_name,
      $password,
      $telephone_number,
      $shipping_address
    );

    $client_email = $client->get_email();
    $full_name = $client->get_full_name();
    $password = $client->get_password();
    $telephone_number = $client->get_telephone_number();
    $shipping_address = $client->get_shipping_address();

    $result = $stmt->execute();
    $stmt->close();

    return $result;
  }
}
