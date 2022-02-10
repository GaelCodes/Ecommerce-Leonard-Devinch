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
            <img class="logo" src="logo.png" alt="Imagen logo not found" srcset="">
            <p class="entreprise-name">Ecommerce Leonard Devinch</p>
            <p class="entreprise-location">Carrretera Inventada 38 </p>
            <p class="entreprise-postal-code">55641 Pronvicia, Y Ciudad Inventada</p>
        </div>
        <div class="order-info">
            <p>Número de factura: 52</p>
            <p>Fecha de factura: 10/02/2022</p>
        </div>
    </header>

    <main>

        <div class="send-to-And-charge-to">
            <div class="send-to">
                <p class="send-to-title">Enviar A</p>
                <p class="client-name">Jack Thomson</p>
                <p class="client-shipping_adress">24 Dummy Street Area, Ciudad Inventada, Provincia</p>
                <p class="client-telephone_number">móvil: +34 648916165</p>
            </div>

            <div class="charge-to">
                <p class="charge-to-title">Facturar A </p>
                <p class="payer-name">Jack Thomson</p>
                <p class="payer-address">24 Dummy Street Area, Ciudad Inventada, Provincia</p>
                <p class="pay-method">Método de pago: Credit Card</p>
                <p class="card-info">Card number: *************4485</p>
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
                <tr>
                    <td>A.I. Artificial Intelligence</td>
                    <td>Rosalia Brownbill</td>
                    <td>1,65€</td>
                    <td>5</td>
                    <td>8,25€</td>
                </tr>
                <tr>
                    <td>3 Holiday Tails (Golden Christmas 2: The Second Tail, A) </td>
                    <td>Nathalie Lynock</td>
                    <td>63,02€</td>
                    <td>10</td>
                    <td>630,20€</td>
                </tr>

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
                                <td>147,00€</td>
                            </tr>
                            <tr>
                                <td>IVA:</td>
                                <td>21%</td>
                            </tr>
                            <tr>
                                <td>TOTAL:</td>
                                <td>147.48€</td>
                            </tr>

                        </table>
                    </td>
                </tr>
            </tfoot>


        </table>



    </main>


</body>

</html>