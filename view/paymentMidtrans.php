<?php
/**
 * Include test library if you are using composer
 * Example: Psysh (debugging library similar to pry in Ruby)
 */
session_start();
$_SESSION['cred'] = $_GET['m'];

// echo $_SESSION['cred'];

$urlIP = 'api-ticket.arisukarno.xyz';

$url = 'https://' . $urlIP . '/items/order?fields=order_id,customer_id.*,invoice_id.invoice_id,invoice_id.invoice_total,ticket_id.event_id.event_name,ticket_id.ticket_price,ticket_id.ticket_id,voucher_id.voucher_id,voucher_id.voucher_code,voucher_id.voucher_discount&filter[customer_id][customer_code]=' . $_SESSION['cred'];

$curl = curl_init();

//get customer ID
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$responseID = curl_exec($curl);
$resultID = json_decode($responseID, true);
$dataLengthID = $resultID["data"];

echo $responseID;

$customer_details = array(
    'first_name'    => $resultID['data'][0]['customer_id']['customer_name'],
    'last_name'     => "",
    'email'         => $resultID['data'][0]['customer_id']['customer_email'],
    'phone'         => $resultID['data'][0]['customer_id']['customer_phone'],
);

$transaction_details = array(
    'order_id' => $resultID['data'][0]['invoice_id']['invoice_id'],
    'gross_amount' => $resultID['data'][0]['invoice_id']['invoice_total'], // no decimal allowed for creditcard
);

$item_details = [];

$item1_details = array(
    'id' => $resultID['data'][0]['ticket_id']['ticket_id'],
    'price' => $resultID['data'][0]['ticket_id']['ticket_price'],
    'quantity' => 1,
    'name' => $resultID['data'][0]['ticket_id']['event_id']['event_name']
);


array_push($item_details, $item1_details);
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
echo "<br>snapToken = ".$snapToken . "<br>";
$base = $_SERVER['REQUEST_URI'];

$clientKey = "SB-Mid-client-tjC8PfiJ-OLB_R61";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | Integrasi midtrans di aplikasi payment sederhana - qadrlabs.com</title>
</head>
<body>
<br>
<br>
<button id="pay-button">Pay!</button>
<pre><div id="result-json">JSON result will appear here after payment:<br></div></pre> 

    <!-- TODO: Remove ".sandbox" from script src URL for production environment. Also input your client key in "data-client-key" -->
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