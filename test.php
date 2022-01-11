<?php

echo "Jalan..";

$date = new DateTime("2022-01-04T08:43:21.000Z");
$date->add(new DateInterval("P1D"));
    
// echo $date->format('c');

$currentDate = new DateTime();
$addDate = new DateTime();
$addDate->add(new DateInterval("P1D"));

if ($addDate > $currentDate) {
    echo $addDate->format('c');
}

// $curl = curl_init();

        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => "http://20.124.214.5:8055/items/registration/1",
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => '',
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,   
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => 'PATCH',
        //     CURLOPT_POSTFIELDS =>'{
        //         "id_participant": 1,
        //         "id_session": 2

        //     }',
        //     CURLOPT_HTTPHEADER => array(
        //         'Content-Type: application/json'
        //     ),
        // ));

        // $voucherURL = "https://api-ticket.arisukarno.xyz/items/voucher";
        // $voucherID = 1;
        // $voucherStok = '71';
        // $voucherReal = (int)$voucherStok - 1;

        // echo $voucherReal;

        // $curl = curl_init();
        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => $voucherURL . "/" . $voucherID,
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => '',
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => 'PATCH',
        //     CURLOPT_POSTFIELDS =>'{
        //     "voucher_stock": ' . $voucherReal . '
        // }',
        //     CURLOPT_HTTPHEADER => array(
        //         'Content-Type: application/json'
        //     ),
        // ));

        // $responseInvID = curl_exec($curl);
        // $resultInvID = json_decode($responseInvID, true);  

        // if (!isset($postResponse['errors'][0]['extensions']['code'])){
        //     echo "Bisaa" . "\n";
        //     echo var_export($responseInvID);
        //     }

        //     $curl = curl_init();
        //     curl_setopt_array($curl, array(
        //         CURLOPT_URL => "https://register.ulin-app.xyz/v1/register",
        //         CURLOPT_RETURNTRANSFER => true,
        //         CURLOPT_ENCODING => '',
        //         CURLOPT_MAXREDIRS => 10,
        //         CURLOPT_TIMEOUT => 0,
        //         CURLOPT_FOLLOWLOCATION => true,
        //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //         CURLOPT_CUSTOMREQUEST => 'POST',
        //         CURLOPT_POSTFIELDS =>'{
        //         "id_participant": "' . $resultID['data'][$x]['customer_id'] . '",
        //         "id_session": "' . $resultID['data'][$x]['ticket_id']['ticket_x_session'][$i]['session_id'] . '",
        //         "ud_ticket : "' . $resultID['data'][$x]['ticket_id']['ticket_type'] . '"
        //     }',
        //         CURLOPT_HTTPHEADER => array(
        //             'Content-Type: application/json'
        //         ),
        //     ));

        //     $response = curl_exec($curl);
        //     $result = json_decode($response, true);

        //     if (isset($result['errors'][0]['extensions']['code'])){
        //         echo $result['errors'][0]['extensions']['code'];
        //     }
        //     else{
        //         echo 'scs';
        //     }

        // $curl = curl_init();
        //     curl_setopt_array($curl, array(
        //         CURLOPT_URL => "https://checkin.nvia.xyz/items/session",
        //         CURLOPT_RETURNTRANSFER => true,
        //         CURLOPT_ENCODING => '',
        //         CURLOPT_MAXREDIRS => 10,
        //         CURLOPT_TIMEOUT => 0,
        //         CURLOPT_FOLLOWLOCATION => true,
        //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //         CURLOPT_CUSTOMREQUEST => 'POST',
        //         CURLOPT_POSTFIELDS =>'{
        //                 "id": 1,
        //                 "session_type": "Coba type",
        //                 "session_desc": "Coba desc"
        //         }',
        //         CURLOPT_HTTPHEADER => array(
        //             'Content-Type: application/json'
        //         ),
        //     ));

        //     $response = curl_exec($curl);
        //     $result = json_decode($response, true);
        //     echo var_export($result) . "\n";


        //     if (isset($result['errors'][0]['extensions']['code'])){
        //         echo $result['errors'][0]['extensions']['code'];
        //     }
        //     else{
        //         echo 'scs';
        //         echo var_export($result) . "\n";
        //     }

        //     curl_close($curl);

?>