<?php

require_once __ROOT__ .
  "/public/common-utilities/artworksDatabaseManagerClass.php";
require_once __ROOT__ .
  "/public/common-utilities/artworksDatabaseManagerClass.php";

class PdfsManager
{
  private string $output_file_name;
  private string $output_dir;
  private string $pdf_path;
  private string $html_path;
  private string $template_path = "tmp-files/order-template/order-template-page.php";

  public function __construct()
  {
  }

  public function create_pdf(
    Client $client,
    Order $order,
    array $purchasedArtworks,
    \Stripe\PaymentIntent $payment_intent
  ) {
    // Generación del nombre (el id de la order)
    $this->output_dir = __DIR__ . "/tmp-files/generated-orders/";
    $this->output_file_name = $order->get_order_id();

    $this->pdf_path = $this->output_dir . $this->output_file_name . ".pdf";
    $this->html_path = $this->output_dir . $this->output_file_name . ".html";

    // Generación del archivo html a partir
    // de la ejecución del template php
    ob_start();
    include $this->template_path;

    $html_resource_id = fopen($this->html_path, "w");

    fwrite($html_resource_id, ob_get_clean());
    fclose($html_resource_id);

    // Ejecución del programa que genera el PDF

    /* 
     El directorio del wkhtmltopdf debe ser referenciado con un variable de
     entorno porque en windows y linux la extensión del programa
     no es la misma.
    */

    //
    $bin_path = realpath(
      __DIR__ . $_ENV["DIR_OF_WKHTMLTOPDF_FROM_WEBHOOK_UTILITIES"]
    );
    $cmd =
      "\"" .
      $bin_path .
      "\" \"--enable-local-file-access\" " .
      "\"" .
      $this->html_path .
      "\" " .
      "\"" .
      $this->pdf_path .
      "\" ";

    /*
     Objective:
    
     " "webhooks-utilities/tmp-files/order-generator/bin/wkhtmltopdf.exe"
       "generated.html" "generated.pdf" "
    */

    exec($cmd);
  }

  public function get_pdf_path(): string
  {
    return $this->pdf_path;
  }
}
