<?php
include_once "databaseManagerClass.php";
require_once "artworksDatabaseManagerClass.php";

class ordersDatabaseManager extends DatabaseManager
{
  function __construct()
  {
    parent::__construct();
  }

  // Returns a order with the autogenerated order_id
  // property
  public function insert_order(Order $order): Order
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

  public function update_order_status(Order $order)
  {
    $order_id = $order->get_order_id();
    $order_status = $order->get_status();

    $stmt = $this->mysqli->prepare(
      "UPDATE ORDERS SET status = '" .
        $order_status .
        "' WHERE order_id =" .
        $order_id
    );

    $update_result = $stmt->execute();
    $stmt->close();

    if (!$update_result) {
      throw new Exception("Error updating order status", 1);
    }
  }

  public function select_orders_by_client(Client $client): array
  {
    $consultResult = $this->mysqli->query(
      "SELECT * FROM ORDERS WHERE client_email ='" .
        $client->get_client_email() .
        "'"
    );

    $ordersArray = [];
    for ($num_row = 0; $num_row < $consultResult->num_rows; $num_row++) {
      $consultResult->data_seek($num_row);
      $row = $consultResult->fetch_assoc();

      $purchasedArtworksDBM = new PurchasedArtworksDatabaseManager();
      $purchasedArtworks = $purchasedArtworksDBM->select_purchased_artworks_by_order_id(
        $row["order_id"]
      );

      // La URL es necesaria porque en el frontend se necesita
      for ($i = 0; $i < count($purchasedArtworks); $i++) {
        $purchasedArtwork = &$purchasedArtworks[$i];

        $artworksDBM = new ArtworksDatabaseManager();
        $artwork = $artworksDBM->selectArtworkByPK(
          $purchasedArtwork->get_artwork_title(),
          $purchasedArtwork->get_artist_email()
        );

        $purchasedArtwork->set_url($artwork->get_url());
      }

      $order = new Order(
        $client,
        $purchasedArtworks,
        $row["order_id"],
        $row["status"],
        $row["order_date"]
      );

      $ordersArray[$num_row] = $order;
    }

    return $ordersArray;
  }

  /*
    Probably useful function in a future
    can delete if not
  */

  public function select_order_by_client_and_id(
    Client $client,
    string $order_id
  ): Order {
    $row = $this->mysqli->query(
      "SELECT * FROM ORDERS WHERE client_email ='" .
        $client->get_client_email() .
        "' AND order_id=" .
        $order_id
    );

    if ($row) {
      $data = $row->fetch_array(MYSQLI_ASSOC);

      // This function wont recover purchasedArtworks data
      $order = new Order(
        $client,
        null,
        $data["order_id"],
        $data["status"],
        $data["order_date"],
        $data["total_artworks_adquired"],
        $data["total_charge"]
      );

      return $order;
    } else {
      throw new Exception(
        "Error selecting order IN: select_order_by_client_and_id, no one found"
      );
    }
  }
}
