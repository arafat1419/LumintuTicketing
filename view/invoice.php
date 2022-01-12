<?php
session_start();
$_SESSION['cred'] = $_GET['m'];
// $customerCode = "3c68bbfc2556f5041e686dbb929fc6e962c690cef05bd77ab50334f466d56e52bdd718336f1e0854225bb517aa3bc8f9694bd1a3bc8d54dc31e4d2100be31652";

date_default_timezone_set("Asia/Bangkok");

$urlIP = 'api-ticket.arisukarno.xyz';

$urlGet = "https://" . $urlIP .  "/items/order?fields=customer_id.*,invoice_id.invoice_id&filter[customer_id][customer_code]=" . $_SESSION['cred'] . "&filter[invoice_id][invoice_status]=pending";

$curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $urlGet,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $responseGet = curl_exec($curl);
        $resultGet = json_decode($responseGet, true);
        $lengthGet = $resultGet["data"];
        curl_close($curl);
        $invoiceId = $lengthGet[0]['invoice_id']['invoice_id'];


$urlInvoice = "https://" . $urlIP .  "/items/order?fields=invoice_id.*,customer_id.customer_name,ticket_id.ticket_type,ticket_id.ticket_price,ticket_id.event_id.event_name,voucher_id.voucher_code,voucher_id.voucher_discount&filter[invoice_id][invoice_id]=" . $invoiceId;

$curl = curl_init();

curl_setopt($curl, CURLOPT_URL, $urlInvoice);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$responseInvoice = curl_exec($curl);
$resultInvoice = json_decode($responseInvoice, true);
$dataInvoice = $resultInvoice["data"];


$field = "";
$fieldVoucher = "";
curl_close($curl);

for ($i = 0; $i < sizeof($dataInvoice); $i++) {
    $customerName = $dataInvoice[$i]['customer_id']['customer_name'];
    $ticketType = $dataInvoice[$i]['ticket_id']['ticket_type'];
    $ticketPrice = $dataInvoice[$i]['ticket_id']['ticket_price'];
  

    $field .= "<tr>
    <td class='text-white' colspan='3'>".$customerName."</td>
    <td class='text-white' colspan='3'>".$ticketType."</td>
    <td class='text-white text-right' colspan='3'>".$ticketPrice."</td>
</tr>";

    if(!is_null($dataInvoice[$i]['voucher_id'])) {
    $voucherCode = $dataInvoice[$i]['voucher_id']['voucher_code'];
    $voucherDiscount = $dataInvoice[$i]['voucher_id']['voucher_discount'];

    $fieldVoucher = "<tr>
    <td class='text-white' colspan='3'>".$voucherCode."</td>
    <td class='text-white text-right' colspan='6'>-".$voucherDiscount."</td>
    </tr>";
}

$eventName = $dataInvoice[0]['ticket_id']['event_id']['event_name'];
// echo $eventName;

$url = 'https://' . $urlIP . '/items/order?fields=order_id,invoice_id.invoice_id,invoice_id.invoice_total,invoice_id.invoice_status,ticket_id.event_id.event_name,ticket_id.ticket_price,ticket_id.ticket_id,voucher_id.voucher_id,voucher_id.voucher_code,voucher_id.voucher_discount&filter[invoice_id][invoice_id]=' . $invoiceId;

$curl = curl_init();

//get customer ID
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$responseID = curl_exec($curl);
$resultID = json_decode($responseID, true);
$dataLengthID = $resultID["data"];

// echo $responseID;

$customer_details = array(
    'first_name'    => $lengthGet[0]['customer_id']['customer_name'],
    'last_name'     => "",
    'email'         => $lengthGet[0]['customer_id']['customer_email'],
    'phone'         => $lengthGet[0]['customer_id']['customer_phone'],
);

$transaction_details = array(
    'order_id' => $resultID['data'][0]['invoice_id']['invoice_id'],
    'gross_amount' => $resultID['data'][0]['invoice_id']['invoice_total'], // no decimal allowed for creditcard
);

$item_details = [];

// $item1_details = array(
//     'id' => $resultID['data'][0]['ticket_id']['ticket_id'],
//     'price' => $resultID['data'][0]['ticket_id']['ticket_price'],
//     'quantity' => 1,
//     'name' => $resultID['data'][0]['ticket_id']['event_id']['event_name']
// );

for ($j = 0; $j < sizeof($dataLengthID); $j++) {
    $item1_details = array(
        'id' => $resultID['data'][$j]['ticket_id']['ticket_id'],
        'price' => $resultID['data'][$j]['ticket_id']['ticket_price'],
        'quantity' => 1,
        'name' => $resultID['data'][$j]['ticket_id']['event_id']['event_name']
    );
    array_push($item_details, $item1_details);
}


if (!is_null($resultID['data'][0]['voucher_id'])){
    $price = $resultID['data'][0]['voucher_id']['voucher_discount'];
    $voucherCode = $resultID['data'][0]['voucher_id']['voucher_code'] . " (VOUCHER)";
    $item2_details = array(
        'id' => $resultID['data'][0]['voucher_id']['voucher_id'],
        'price' => -$price,
        'quantity' => 1,
        'name' => $voucherCode
    );
    array_push($item_details, $item2_details);

}

curl_close($curl);


require_once dirname(__FILE__) . '../../vendor/autoload.php';

require_once dirname(__FILE__) . '../../vendor/midtrans/midtrans-php/Midtrans.php';
require_once dirname(__FILE__) . '../../vendor/midtrans/midtrans-php/tests/MT_Tests.php';
require_once dirname(__FILE__) . '../../vendor/midtrans/midtrans-php/tests/utility/MtFixture.php';

// Set your Merchant Server Key
\Midtrans\Config::$serverKey = 'SB-Mid-server-ZVYp2uKxdvShYB-YHJug2akF';
// Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
\Midtrans\Config::$isProduction = false;
// Set sanitization on (default)
\Midtrans\Config::$isSanitized = true;
// Set 3DS transaction for credit card to true
\Midtrans\Config::$is3ds = true;
// Fill transaction details
$transaction = array(
    // 'enabled_payments' => $enable_payments,
    'transaction_details' => $transaction_details,
    'customer_details' => $customer_details,
    'item_details' => $item_details,
);

$snapToken = Midtrans\Snap::getSnapToken($transaction);
// echo "<br>snapToken = ".$snapToken . "<br>";
$base = $_SERVER['REQUEST_URI'];

$clientKey = "SB-Mid-client-tjC8PfiJ-OLB_R61";


}



$invoiceTotal=$dataInvoice[0]['invoice_id']['invoice_total'];

$invoiceDate=new DateTime($dataInvoice[0]['invoice_id']['invoice_date']);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
        integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.jqueryui.min.css">
    <!-- CSS Independent -->
    <link rel="stylesheet" href="../public/css/invoice.css">


    <title>Invoice | Lumintu Event</title>
</head>

<body>
<body>
    <div class="container-fluid container-parent">
        <div class="row">
            <div class="col-12 mx-auto isi">
                <div id="notCompleted">
                    <div class="header mt-3">
                        <p class="h2 title-status">THANKS FOR YOUR ORDER</p>
                    </div>
                    <div class="content text-center mt-5">
                            <div class="info">
                            <p class="h5 text-white text-center"><?php echo $eventName ?></p>
                            <p class="h5 text-white text-center"><?php echo $invoiceDate->format('l, M d Y ') . "at" . $invoiceDate->format(' h:i A')?></p>
                    </div>    
            <table class="table">
            <thead>
                <tr>
                    <th colspan="3" class="name-td text-white">NAMA</th>
                    <th colspan="3" class="no-sort ticket-td text-white">TICKET</th>
                    <th colspan="3" class="no-sort price-td text-white text-right">PRICE</th>
                </tr>
            </thead>
            <tbody>
                
                <?php echo $field ?>
            </tbody>
            <tfoot>
            <tr>
                <th class='text-white' colspan='3'>VOUCHER</th>
                <th class='text-white text-right' colspan='6'>DISC</th>
                <?php echo $fieldVoucher ?>
                </tr>
                <tr class="text-center">
                    <th colspan="6" class="text-right text-white">TOTAL INVOICE :</th>
                    <td>
                    <input type="email" class="input-status w-100 text-right" id="total-harga" name="total-harga" value="<?php echo $invoiceTotal?>" readonly />
                    </td>
                </tr>
                
            </tfoot>
        </table>
                        
                        <div class="container mt-5">
                                    <button type="submit" value="submit" name="submit" class="btn rounded py-1 px-5 btn-checkout " id="pay-button">BAYAR</button>
                                </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Jquery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!-- Bootstrap CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns"
        crossorigin="anonymous"></script>

    <!-- Font Awesome CDN -->
    <script src="https://use.fontawesome.com/7a7a4d3981.js"></script>

    <!-- SweetAlert2 CDN -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- OwlCarousel2 CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"
        integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Independent Javascript -->
    <script src="../public/js/uploadPayment.js"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(){
            // SnapToken acquired from previous step
            snap.pay('<?php echo $snapToken?>', {
                // Optional
                onSuccess: function(result){
                    /* You may add your own js here, this is just example */ 
                    document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                },
                // Optional
                onPending: function(result){
                    /* You may add your own js here, this is just example */ 
                    document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                },
                // Optional
                onError: function(result){
                    /* You may add your own js here, this is just example */ 
                    document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                }
            });
        };
    </script>  
</body>

</html>