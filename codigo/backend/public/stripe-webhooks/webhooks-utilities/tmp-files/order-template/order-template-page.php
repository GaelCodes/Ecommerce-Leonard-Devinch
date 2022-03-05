<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <header>
        <div class="entreprise-info">
            <img class="logo" src="ecommerce-leonard-devinch-Logo.png" alt="Imagen logo not found" srcset="">
            <p class="entreprise-name">Ecommerce Leonard Devinch</p>
            <p class="entreprise-location">Carrretera Inventada 38 </p>
            <p class="entreprise-postal-code">55641 Pronvicia, Y Ciudad Inventada</p>
        </div>
        <div class="order-info">
            <p>Número de factura: <?php echo $order->get_order_id(); ?></p>
            <p>Fecha de factura: <?php echo $order->get_order_date(); ?></p>
        </div>
    </header>

    <main>

        <div class="send-to-And-charge-to">
            <div class="send-to">
                <p class="send-to-title">Enviar A</p>
                <p class="client-name"><?php echo $client->get_full_name(); ?></p>
                <p class="client-shipping_adress"><?php echo $client->get_shipping_address(); ?></p>
                <p class="client-telephone_number">móvil: <?php echo $client->get_telephone_number(); ?></p>
            </div>

            <div class="charge-to">
                <p class="charge-to-title">Facturar A </p>
                <p class="payer-name"><?php echo $client->get_full_name(); ?></p>
                <p class="payer-address"><?php echo $client->get_shipping_address(); ?></p>
                <p class="pay-method">
                    <?php echo $payment_intent->charges->data[0]
                      ->payment_method_details->type; ?>
                  
                </p>
                <p class="card-info">
                    <?php echo $payment_intent->charges->data[0]
                      ->payment_method_details->card->brand .
                      ":  ************" .
                      $payment_intent->charges->data[0]->payment_method_details
                        ->card->last4; ?>
                      
                  
                </p>
            </div>
        </div>

        <table class="articles-table">
            <thead>
                <tr>
                    <th>
                        Título
                    </th>
                    <th>
                        Artista
                    </th>
                    <th>
                        Precio
                    </th>
                    <th>
                        Cantidad
                    </th>
                    <th>
                        Total
                    </th>
                </tr>
            </thead>
            <tbody>

            <?php for ($i = 0; $i < count($purchasedArtworks); $i++) {
              $purchased_artwork = $purchasedArtworks[$i];

              $title = $purchased_artwork->get_artwork_title();
              $artist_full_name = $purchased_artwork
                ->get_artist()
                ->get_full_name();

              $price_by_unit = number_format(
                $purchased_artwork->get_price_by_unit(),
                2,
                ","
              );
              $units = $purchased_artwork->get_units();

              $total_by_artwork =
                $purchased_artwork->get_units() *
                $purchased_artwork->get_price_by_unit();

              $total_by_artwork = number_format($total_by_artwork, 2, ",");

              echo "           
            <tr>
                <td>$title</td>
                <td>$artist_full_name</td>
                <td>$price_by_unit €</td>
                <td>$units</td>
                <td>$total_by_artwork €</td>
            </tr>
            
            
            ";
            } ?>

            </tbody>

            <tfoot>
                <tr>
                    <td class="thanks" colspan="3">
                        <p> Gracias por tu compra ^^ </p>
                    </td>
                    <td colspan="2">


                        <table class="summary-table">
                            <tr>
                                <td>SUBTOTAL:</td>
                                <td><?php echo number_format(
                                  $order->get_total_charge(),
                                  2,
                                  ","
                                ) . " €"; ?></td>
                            </tr>
                            <tr>
                                <td>IVA:</td>
                                <td>0%</td>
                            </tr>
                            <tr>
                                <td>TOTAL:</td>
                                <td><?php echo number_format(
                                  $order->get_total_charge(),
                                  2,
                                  ","
                                ) . " €"; ?></td>
                            </tr>

                        </table>
                    </td>
                </tr>
            </tfoot>


        </table>



    </main>


</body>

</html>