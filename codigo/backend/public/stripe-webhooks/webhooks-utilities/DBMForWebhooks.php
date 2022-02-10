<?php

require_once __ROOT__ .
  "/public/apis-utilities/v1/clientsDatabaseManagerClass.php";
require_once __ROOT__ .
  "/public/apis-utilities/v1/ordersDatabaseManagerClass.php";
require_once __ROOT__ .
  "/public/apis-utilities/v1/purchasedArtworksDatabaseManagerClass.php";
require_once __ROOT__ .
  "/public/apis-utilities/v1/artworksDatabaseManagerClass.php";
require_once __ROOT__ .
  "/public/apis-utilities/v1/clientsDatabaseManagerClass.php";

// Class Database Manager For Stripe's Webhooks

class DBMForWebhooks
{
  private ClientsDatabaseManager $clientDBM;
  private OrdersDatabaseManager $ordersDBM;
  private PurchasedArtworksDatabaseManager $purchasedArtworksDBM;
  private ArtworksDatabaseManager $artworksDBM;

  public function __construct()
  {
    // TODO: Optimizar el cierre y apertura de conexiones con la BBDD
    $this->clientDBM = new ClientsDatabaseManager();
    $this->ordersDBM = new OrdersDatabaseManager();
    $this->purchasedArtworksDBM = new PurchasedArtworksDatabaseManager();
    $this->artworksDBM = new ArtworksDatabaseManager();
  }

  public function get_client_by_stripe_id(string $stripe_customer_id): Client
  {
    $filters = ["stripe_customer_id" => $stripe_customer_id];
    $client = $this->clientDBM->select_clients_by_filters($filters)[0];

    return $client;
  }

  public function get_order_by_id(string $order_id)
  {
    $order = $this->ordersDBM->select_order_by_id($order_id);

    return $order;
  }

  public function get_purchased_artworks_by_order_id(string $order_id): array
  {
    // TODO: Desarrollar método select_filtered_artworks
    // en purchasedArtworksDBM
    $filters = ["order_id" => $order_id];
    $purchased_artworks = $this->purchasedArtworksDBM->select_filtered_purchased_artworks(
      $filters
    );

    return $purchased_artworks;
  }

  public function update_order_status(Order $order)
  {
    $this->ordersDBM->update_order_status($order);
  }

  public function update_available_quantity(array $purcharsedArtworks)
  {
    for ($i = 0; $i < count($purcharsedArtworks); $i++) {
      $purcharsedArtwork = $purcharsedArtworks[$i];

      // Retrieve current available_quantity
      $title = $purcharsedArtwork->get_artwork_title();
      $artist_email = $purcharsedArtwork->get_artist_email();

      $artwork = $this->artworksDBM->selectArtworkByPK($title, $artist_email);

      // TODO: Calculate that there is enough units to buy

      // TODO: Investigate if it's possible to cancel the
      //       paymentIntent at this point

      // Update available_quantity
      $updated_available_quantity =
        $artwork->get_available_quantity() - $purcharsedArtwork->get_units();
      $artwork->set_available_quantity($updated_available_quantity);

      $this->artworksDBM->updateArtwork($artwork);
    }
  }
}
