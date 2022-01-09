<?php

$urlIP = 'api-ticket.arisukarno.xyz';

$orderURL = 'https://' . $urlIP . '/items/order';
$voucherURL = 'https://' . $urlIP . '/items/voucher';


// GET VOUCHER
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $orderURL . '?fields=voucher_id.*,invoice_id.invoice_id,invoice_id.invoice_status');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$responseID = curl_exec($curl);
$resultID = json_decode($responseID, true);
curl_close($curl);
// echo '<script> console.log('. $responseID .') </script>';
// echo $responseID;

$dataLengthID = count($resultID["data"]);

// echo "<br><br><br>";
// echo "<br> Data Length = " . $dataLengthID . "<br>";

$voucherId = [];

for ($i = 0; $i < $dataLengthID; $i++) {
    // try {
    //     $voucherCode = $resultID["data"][$i]["voucher_id"]["voucher_code"];
    //     if (!is_null($voucherCode)) {
    //     echo $voucherCode . "<br>";
    // }
    // } catch (Exception $e) {
    //     echo $e;   
    // }

    $voucherInOrder = $resultID["data"][$i]["voucher_id"];

    if (!is_null($voucherInOrder)) {
        // $voucherCode = $resultID["data"][$i]["voucher_id"]["voucher_code"];
        // echo $voucherCode . "<br>";

        if (!in_array($resultID["data"][$i]["voucher_id"]["voucher_id"], $voucherId)) {
            array_push($voucherId, $resultID["data"][$i]["voucher_id"]["voucher_id"]);
        }
        // $invoiceId = $resultID["data"][$i]["voucher_id"]["invoice_id"];
        // $voucherStock = 0;


    } else {
        // echo "Not use voucher" . "<br>";
    }
}



for ($i = 0; $i < count($voucherId); $i++) {
    // echo $voucherId[$i] . "<br>";
    // echo "<br><br><br>";

    // GET VOUCHER
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $orderURL . '?fields=voucher_id.*,invoice_id.invoice_id,invoice_id.invoice_status&filter[voucher_id.voucher_id]=' . $voucherId[$i]);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $responseID = curl_exec($curl);
    $resultID = json_decode($responseID, true);
    curl_close($curl);


    // echo '<script> console.log('. $responseID .') </script>';
    // echo $responseID . "<br>";
    $dataLengthID = count($resultID["data"]);


    $voucherStock = $resultID["data"][0]["voucher_id"]["voucher_stock"];
    $invoiceExpired = 0;
    $invoiceId = [];

    for($j = 0; $j < $dataLengthID; $j++) {
        if(!in_array($resultID["data"][0]["invoice_id"]["invoice_id"], $invoiceId)) {
            if($resultID["data"][0]["invoice_id"]["invoice_status"] == 2) {
                // echo $resultID["data"][0]["invoice_id"]["invoice_status"] . "<br>";
                $invoiceExpired++;
            }
            array_push($invoiceId ,$resultID["data"][0]["invoice_id"]["invoice_id"]);
        }
    }

    $getVoucherAvailable = $resultID["data"][0]["voucher_id"]["voucher_available"];
    $voucherAvailable = $voucherStock - (count($invoiceId) + 1) + $invoiceExpired;
    // echo "Voucher Available = " . $voucherAvailable . "<br><br><br>";

    if ($getVoucherAvailable != $voucherAvailable) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $voucherURL . "/" . $voucherId[$i],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PATCH',
            CURLOPT_POSTFIELDS =>'{
            "voucher_available": ' . $voucherAvailable . '
        }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $responseInvID = curl_exec($curl);
        $resultInvID = json_decode($responseInvID, true);  

        curl_close($curl);


        if (!isset($postResponse['errors'][0]['extensions']['code'])){
            // echo "Bisaa" . "\n";
            // echo var_export($responseInvID);
        }
    }
}


// GET VOUCHER BY ID

?>