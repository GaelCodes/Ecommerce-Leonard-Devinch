<?php
require_once "databaseManagerClass.php";
require_once "clientClass.php";

class ClientsDatabaseManager extends DatabaseManager
{
  public function __construct()
  {
    parent::__construct();
  }

  public function insert_client(Client $client)
  {
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

    if ($result == false) {
      throw new Exception("Error inserting new client to database", 1);
    }
  }

  public function select_client_by_id(int $id): Client
  {
    $query = "SELECT * FROM CLIENTS WHERE client_id = " . $id;
    $row = $this->mysqli->query($query);

    if ($row) {
      $data = $row->fetch_array(MYSQLI_ASSOC);

      $client = new Client(
        $data["client_id"],
        $data["client_email"],
        $data["stripe_customer_id"],
        $data["password"],
        $data["full_name"],
        $data["shipping_address"],
        $data["telephone_number"]
      );
    } else {
      throw new Exception(
        "Error selecting client from database by client_id",
        1
      );
    }

    return $client;
  }

  public function update_client(Client $client)
  {
    $stmt = $this->mysqli->prepare(
      "UPDATE CLIENTS SET client_email = ?, stripe_customer_id = ?, full_name = ?, password = ?, telephone_number = ?, shipping_address = ? WHERE client_email = ?"
    );

    $stmt->bind_param(
      "ssssdss",
      $client_email,
      $stripe_customer_id,
      $full_name,
      $password,
      $telephone_number,
      $shipping_address,
      $client_email
    );

    $client_email = $client->get_email();
    $stripe_customer_id = $client->get_stripe_customer_id();
    $full_name = $client->get_full_name();
    $password = $client->get_password();
    $telephone_number = $client->get_telephone_number();
    $shipping_address = $client->get_shipping_address();

    $result = $stmt->execute();
    $stmt->close();

    return $result;
  }

  public function select_client_by_email(string $client_email): Client
  {
    $query =
      "SELECT * FROM CLIENTS where client_email = '" . $client_email . "'";
    $consultResult = $this->mysqli->query($query);

    if ($consultResult) {
      $data = $consultResult->fetch_array(MYSQLI_ASSOC);

      $client = new Client(
        $data["client_id"],
        $data["client_email"],
        $data["stripe_customer_id"],
        $data["password"],
        $data["full_name"],
        $data["shipping_address"],
        $data["telephone_number"]
      );
    } else {
      throw new Exception("Error selecting client by email", 1);
    }

    return $client;
  }

  /*
  @param array $filters, array with
  filter name as key and value of the filter as value, eg:
  $filters = [
    "ids" => [0,1,2...],
    "client_email" => "ha@sad.com",
    "stripe_customer_id" => "dh5h...",
    "full_name" => "partial nam"
    ])

  @return array of Clients
  */
  public function select_clients_by_filters(array $filters): array
  {
    $query = $this->prepare_filtered_query($filters);

    $row = $this->mysqli->query($query);

    if ($row) {
      for ($num_row = 0; $num_row < $row->num_rows; $num_row++) {
        $row->data_seek($num_row);
        $data = $row->fetch_assoc();

        $client = new Client(
          $data["client_id"],
          $data["client_email"],
          $data["stripe_customer_id"],
          $data["password"],
          $data["full_name"],
          $data["shipping_address"],
          $data["telephone_number"]
        );

        $clients[$num_row] = $client;
      }

      return $clients;
    } else {
      throw new Exception(
        "Error trying to select clients by filters or clients not founds",
        1
      );
    }
  }

  private function prepare_filtered_query(array $filters): string
  {
    $query = "SELECT * FROM CLIENTS WHERE ";

    if (isset($filters["ids"])) {
      // Objective: "id IN ( 0, 1, 2...) AND "
      $total_ids = count($filters["ids"]);
      $query .= " client_id IN (";

      for ($i = 0; $i < $total_ids; $i++) {
        $query .= $filters["ids"][$i];

        $last = $total_ids - $i == 1;
        if (!$last) {
          $query .= ",";
        }
      }

      $query .= ") AND ";
    }

    if (isset($filters["client_email"])) {
      $query .= " client_email = " . $filters["client_email"] . " AND";
    }
    if (isset($filters["stripe_customer_id"])) {
      $query .=
        " stripe_customer_id = '" . $filters["stripe_customer_id"] . "' AND ";
    }

    if (isset($filters["full_name"])) {
      $query .= " full_name LIKE '%" . $filters["full_name"] . "%' AND ";
    }

    // Eliminación del último AND
    $query = preg_replace("/AND $/", "", $query);
    return $query;
  }
}
