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
    $keyFilePath = $_ENV["STORAGE_SERVICE_ACCOUNT_KEY_FILE_PATH"];
    $keyFilePath = realpath($keyFilePath);

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
    $storage_object_path = "/{$client->get_client_email()}/{$order->get_order_id()}.pdf";
    $object = $this->bucket->object($storage_object_path);

    $file_path = __DIR__ . "/tmp-files/${$order->get_order_id()}.pdf";
    $stream = $object->downloadToFile($file_path);

    // Se podrían hacer cosas interesantes con el objeto devuelto
    // $stream Psr\Http\Message\StreamInterface

    return $file_path;
  }
}
