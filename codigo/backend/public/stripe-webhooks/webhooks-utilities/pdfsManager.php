<?php

class PdfsManager
{
  private string $output_file_name;

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
    $this->output_file_name = $order->get_order_id();

    // Generación del archivo html a partir
    // de la ejecución del template php
    ob_start();
    include "tmp-files/order-template/order-template-page.php";
    $order_html_file = fopen(
      __DIR__ .
        "/tmp-files/generated-orders/" .
        $this->output_file_name .
        ".html",
      "w"
    );
    fwrite($order_html_file, ob_get_clean());
    fclose($order_html_file);

    // Ejecución del programa que genera el PDF

    /* 
     El directorio del wkhtmltopdf debe ser referenciado con un variable de
     entorno porque en windows y linux la extensión del programa
     no es la misma.
    */

    //
    $cmd =
      "\"" .
      realpath(__DIR__ . $_ENV["DIR_OF_WKHTMLTOPDF_FROM_WEBHOOK_UTILITIES"]) .
      "\" \"--enable-local-file-access\" " .
      "\"" .
      realpath(__DIR__) .
      "/tmp-files/generated-orders/{$this->output_file_name}.html" .
      "\" " .
      "\"" .
      realpath(__DIR__) .
      "/tmp-files/generated-orders/{$this->output_file_name}.pdf" .
      "\" ";

    /*
     Objective:
    
     " "webhooks-utilities/tmp-files/order-generator/bin/wkhtmltopdf.exe"
       "template.html" "generated.pdf" "
    */

    exec($cmd);
  }
}
