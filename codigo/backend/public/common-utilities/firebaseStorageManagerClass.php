<?php
require __DIR__ . "/vendor/autoload.php";

use Google\Cloud\Storage\StorageClient;
use Google\Cloud\Storage\Bucket;

class FirebaseStorageManager
{
  private StorageClient $storage_client;
  private Bucket $bucket;

  public function __construct()
  {
    $projectId = "ecommerce-leonard-devinch";
    $keyFilePath = __DIR__ . $_ENV["STORAGE_SERVICE_ACCOUNT_KEY_FILE_PATH"];
    $keyFilePath = realpath($keyFilePath);

    error_log("INICIO ARCHIVO KEYPATH");
    error_log($keyFilePath);
    error_log("INICIO ARCHIVO KEYPATH");

    $this->storage_client = new StorageClient([
      "projectId" => $projectId,
      "keyFilePath" => $keyFilePath,
    ]);

    $this->bucket = $this->storage_client->bucket(
      "ecommerce-leonard-devinch.appspot.com"
    );
  }

  public function upload_pdf(Client $client, Order $order, string $pdf_path)
  {
    $dir_name = $client->get_client_email();
    $file_name = "order-" . $order->get_order_id();

    $options = [
      "name" => "users/{$dir_name}/{$file_name}.pdf",
    ];

    // TODO: May need surround with Try catch to trait errors
    $this->bucket->upload(fopen($pdf_path, "r"), $options);
  }

  public function download_pdf(Client $client, Order $order): string
  {
    $client_email = $client->get_client_email();
    $order_id = $order->get_order_id();

    $storage_object_path = "users/{$client_email}/order-{$order_id}.pdf";
    $object = $this->bucket->object($storage_object_path);

    $file_path = __DIR__ . "/tmp-files/order-{$order_id}.pdf";
    $object->downloadToFile($file_path);

    return realpath($file_path);
  }
}
