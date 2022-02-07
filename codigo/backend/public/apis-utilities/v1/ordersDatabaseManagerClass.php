<?php
include_once "databaseManagerClass.php";

class ordersDatabaseManager extends DatabaseManager
{
  function __construct()
  {
    parent::__construct();
  }

  public function insert_order(Order $order)
  {
    $stmt = $this->mysqli->prepare(
      "INSERT INTO ORDERS(client_email, total_charge, order_date, total_artworks_adquired) VALUES(?,?,?,?)"
    );

    $stmt->bind_param(
      "sdsi",
      $client_email,
      $total_charge,
      $order_date,
      $total_artworks_adquired
    );

    $client_email = $order->get_client_email();
    $total_charge = $order->get_total_charge();
    $order_date = $order->get_order_date();
    $total_artworks_adquired = $order->get_total_artworks_adquired();

    $stmt->execute();

    $order->set_order_id($stmt->insert_id);

    $stmt->close();

    return $order;
  }
}
